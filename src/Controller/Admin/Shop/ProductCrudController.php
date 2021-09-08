<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use Adeliom\EasyShopBundle\Form\Admin\ProductAssociationsField;
use Adeliom\EasyShopBundle\Form\Admin\ProductAttributesField;
use Adeliom\EasyShopBundle\Form\Type\ProductBundle\ProductAssociationsType;
use Adeliom\EasyShopBundle\Form\Type\ProductBundle\ProductTaxonType;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Taxonomy\Taxon;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAttributeValueType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductGenerateVariantsType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantGenerationType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductVariantType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Generator\ProductVariantGeneratorInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Adeliom\EasyShopBundle\Form\Type\ProductBundle\ChannelCollectionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $crudUrlGenerator;
    private AdminContextProvider $adminContextProvider;
    private ProductFactoryInterface $productFactory;
    private ProductVariantFactoryInterface $productVariantFactory;
    private ProductVariantRepositoryInterface $productVariantRepository;
    private EntityManagerInterface $productManager;
    private EntityManagerInterface $productVariantManager;

    public function __construct(
        AdminUrlGenerator       $crudUrlGenerator,
        AdminContextProvider    $adminContextProvider,
        ProductFactoryInterface $productFactory,
        ProductVariantFactoryInterface $productVariantFactory,
        ProductVariantRepositoryInterface $productVariantRepository,
        EntityManagerInterface $productManager,
        EntityManagerInterface $productVariantManager
    )
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->adminContextProvider = $adminContextProvider;
        $this->productFactory = $productFactory;
        $this->productVariantFactory = $productVariantFactory;
        $this->productVariantRepository = $productVariantRepository;
        $this->productManager = $productManager;
        $this->productVariantManager = $productVariantManager;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@EasyEditor/form/editor_widget.html.twig')
            ->addFormTheme('@EasyFields/form/association_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig')
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')

            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        $url = $this->crudUrlGenerator->setController(self::class)->setAction(Action::NEW);

        $actions = parent::configureActions($actions);
        $addTypes = [ 'simple_product', 'configurable_product' ];

        foreach ($addTypes as $key ) {
            $newAdd = Action::new($key, 'sylius.ui.'.$key)->linkToUrl((clone $url)->set("productType", $key))->createAsGlobalAction()->setCssClass("btn btn-primary");
            $actions->add(Crud::PAGE_INDEX, $newAdd);
        }
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);

        $manageVariant = Action::new("manage_variant", 'sylius.ui.manage_variants')->linkToCrudAction("manageVariants");
        $actions->add(Crud::PAGE_INDEX, $manageVariant);

        $manageVariantEdit = Action::new("manage_variant", 'sylius.ui.manage_variants')->linkToCrudAction("manageVariants")->setCssClass("btn btn-secondary");
        $actions->add(Crud::PAGE_EDIT, $manageVariantEdit);

        return $actions;
    }

    public function informationFields(string $pageName, AdminContext $context): iterable
    {
        yield FormField::addPanel("sylius.ui.details")->collapsible()->renderCollapsed(false);
        yield TextField::new('code')->setLabel('sylius.ui.code');
        yield BooleanField::new('enabled')->setLabel('sylius.ui.enabled')->renderAsSwitch(in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT]));

        if ($this->isSimpleProduct()) {
            yield BooleanField::new('variant.shippingRequired')->setLabel('sylius.form.variant.shipping_required');
        }else {
            yield FormTypeField::new('options', 'sylius.form.product.options', ProductOptionChoiceType::class)
                ->setFormTypeOptions([
                    'required' => false,
                    'multiple' => true,
                    "attr" => ["data-ea-widget" => "ea-autocomplete"]
                ])
                ->hideOnIndex();
            yield ChoiceField::new('variantSelectionMethod')->setLabel('sylius.form.product.variant_selection_method')->setRequired(true)
                ->setChoices(array_flip(\Sylius\Component\Core\Model\Product::getVariantSelectionMethodLabels()))
                ->hideOnIndex();
        }

        yield FormTypeField::new('channels', 'sylius.form.product.channels', ChannelChoiceType::class)
            ->hideOnIndex()
            ->setFormTypeOption("multiple", true)
            ->setFormTypeOption("expanded", true);
    }

    public function inventoryFields(string $pageName, AdminContext $context): iterable
    {
        if ($this->isSimpleProduct()) {
            yield FormField::addPanel("sylius.ui.inventory")->collapsible()->renderCollapsed();
            yield NumberField::new('variant.onHand')->setLabel('sylius.form.variant.on_hand');
            yield BooleanField::new('variant.tracked')->setLabel('sylius.form.variant.tracked')->setHelp('sylius.form.variant.tracked_help');
        }
    }

    public function shippingFields(string $pageName, AdminContext $context): iterable
    {
        if ($this->isSimpleProduct()) {
            yield FormField::addPanel("sylius.ui.shipping")->collapsible()->renderCollapsed();

            yield FormTypeField::new('variant.shippingCategory', 'sylius.form.product_variant.shipping_category', ShippingCategoryChoiceType::class)
                ->setFormTypeOptions(["attr" => ["data-ea-widget" => "ea-autocomplete"]]);
            yield NumberField::new('variant.width','sylius.form.variant.width')->setColumns(4);
            yield NumberField::new('variant.height','sylius.form.variant.height')->setColumns(4);
            yield NumberField::new('variant.depth','sylius.form.variant.depth')->setColumns(4);
            yield NumberField::new('variant.weight','sylius.form.variant.weight')->setColumns(12);
        }
    }

    public function taxesFields(string $pageName, AdminContext $context): iterable
    {
        if ($this->isSimpleProduct()) {
            yield FormField::addPanel("sylius.ui.taxes")->collapsible()->renderCollapsed();

            yield FormTypeField::new('variant.taxCategory', 'sylius.form.product_variant.tax_category', TaxCategoryChoiceType::class)
                ->setFormTypeOptions(["attr" => ["data-ea-widget" => "ea-autocomplete"]]);
        }
    }

    public function pricingFields(string $pageName, AdminContext $context): iterable
    {
        if ($this->isSimpleProduct()) {
            yield FormField::addPanel("sylius.ui.pricing")->collapsible()->renderCollapsed();

            yield FormTypeField::new('virtualVariantChannelPricing', 'sylius.form.variant.price', ChannelCollectionType::class)
                ->setFormTypeOptions(["label" => false]);
        }
    }

    public function metaFields(string $pageName, AdminContext $context): iterable
    {
        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'slug' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'description' => [
                'field_type' => TextareaType::class,
                'required' => false,
            ],
            'shortDescription' => [
                'field_type' => TextareaType::class,
                'required' => false,
            ],
            'metaKeywords' => [
                'field_type' => TextType::class,
                'required' => false,
            ],
            'metaDescription' => [
                'field_type' => TextareaType::class,
                'required' => false,
            ],
        ];

        yield FormField::addPanel("Contenus")->collapsible()->renderCollapsed();
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

    public function taxonomyFields(string $pageName, AdminContext $context): iterable
    {
        yield FormField::addPanel("Taxonomy")->collapsible()->renderCollapsed();
        yield AssociationField::new('mainTaxon')
            ->setFormTypeOption('class', Taxon::class)
            ->setFormTypeOption('choice_value', "code")
            ->setFormTypeOption('choice_label', function ($item) {
                return $item->getTree(" / ");
            })
            ->setCrudController(TaxonCrudController::class)
            ->hideOnIndex();

        yield AssociationField::new('productTaxons')
            ->setFormType(ProductTaxonType::class)
            ->setFormTypeOption('class', Taxon::class)
            ->setFormTypeOption('product', $this->adminContextProvider->getContext()->getEntity()->getInstance())
            ->setCrudController(TaxonCrudController::class)
            ->hideOnIndex();
    }

    public function attributesFields(string $pageName, AdminContext $context): iterable
    {
        yield FormField::addPanel("Attributes")->collapsible()->renderCollapsed();
        yield ProductAttributesField::new("attributes", false)
            ->hideOnIndex();
    }

    public function associationFields(string $pageName, AdminContext $context): iterable
    {
        yield FormField::addPanel("Associations")->collapsible()->renderCollapsed();
        yield ProductAssociationsField::new("associations", false)
            ->hideOnIndex();
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->adminContextProvider->getContext();

        yield from $this->informationFields($pageName, $context);
        yield from $this->pricingFields($pageName, $context);
        yield from $this->metaFields($pageName, $context);
        yield from $this->inventoryFields($pageName, $context);
        yield from $this->shippingFields($pageName, $context);
        yield from $this->taxesFields($pageName, $context);
        yield from $this->taxonomyFields($pageName, $context);
        yield from $this->attributesFields($pageName, $context);
        yield from $this->associationFields($pageName, $context);

        if ($this->isSimpleProduct()) {

//            yield FormField::addPanel("Variant")->collapsible()->renderCollapsed();
//            yield FormTypeField::new('variant', '', ProductVariantType::class)
//                ->setFormTypeOptions([
//                    'property_path' => 'variants[0]',
//                    'constraints' => [
//                        new Valid(),
//                    ]
//                ])
//                ->setTemplatePath("@EasyShop/form/admin_product.html.twig")
        }


    }

    public function new(AdminContext $context)
    {
        global $productType;
        $productType = $context->getRequest()->query->get("productType");
        return parent::new($context);
    }

    public function createEntity(string $entityFqcn)
    {
        if($this->isSimpleProduct()){
            return $this->productFactory->createWithVariant();
        }
        return $this->productFactory->createNew();

    }

    protected function isSimpleProduct(): bool
    {
        global $productType;
        /**
         * @var Product $entity
         */
        $entity = $this->adminContextProvider->getContext()->getEntity()->getInstance();
        return ((!empty($productType) && $productType === 'simple_product') || (!empty($entity) && $entity->getId() && $entity->isSimple()));
    }

    public function manageVariants(AdminContext $context): Response
    {
        /** @var \Sylius\Component\Product\Model\Product $product */
        $product = $context->getEntity()->getInstance();

        return $this->render('@EasyShop/crud/variant/list.html.twig', [
            'product' => $product
        ]);
    }

    public function createVariant(AdminContext $context): Response
    {
        $variant = $this->productVariantFactory->createForProduct($context->getEntity()->getInstance());
        $form = $this->createForm(ProductVariantType::class, $variant);

        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $variant = $form->getData();
            $this->productVariantManager->persist($variant);
            $this->productVariantManager->flush();
            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($variant->getProduct()->getId())->setAction("manageVariants")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/variant/new_variant.html.twig', [
            'product' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function editVariant(AdminContext $context): Response
    {
        $variant = $this->productVariantRepository->find($context->getRequest()->query->get("variantId"));
        if (!($variant instanceof ProductVariantInterface)){
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProductVariantType::class, $variant);
        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $variant = $form->getData();
            $this->productVariantManager->persist($variant);
            $this->productVariantManager->flush();
            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($variant->getProduct()->getId())->setAction("manageVariants")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/variant/edit_variant.html.twig', [
            'product' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function deleteVariant(AdminContext $context): Response
    {
        $variant = $this->productVariantRepository->find($context->getRequest()->query->get("variantId"));
        if (!($variant instanceof ProductVariantInterface)){
            throw new NotFoundHttpException();
        }

        $this->productVariantManager->remove($variant);
        $this->productVariantManager->flush();

        $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($variant->getProduct()->getId())->setAction("manageVariants")->generateUrl();
        return $this->redirect($url);
    }

    public function batchDeleteVariants(AdminContext $context): Response
    {
        foreach ($context->getRequest()->get("batchActionEntityIds", []) as $i){
            $coupon = $this->productVariantRepository->find($i);
            if (!$coupon) {
                continue;
            }
            $this->productVariantManager->remove($coupon);
            $this->productVariantManager->flush();
        }
        $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($coupon->getPromotion()->getId())->setAction("manageVariants")->generateUrl();
        return $this->redirect($url);
    }

    public function generateVariants(AdminContext $context): Response
    {
        $product = $context->getEntity()->getInstance();
        $form = $this->createForm(ProductGenerateVariantsType::class, $product);
        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $this->productManager->persist($product);
            $this->productManager->flush();

            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($context->getEntity()->getPrimaryKeyValue())->setAction("manageVariants")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/variant/generate_variants.html.twig', [
            'product' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function manageStock(AdminContext $context): Response
    {
        $tracked =$this->productVariantRepository->findBy([
            "tracked" => true
        ]);

        return $this->render('@EasyShop/crud/variant/stocks.html.twig', [
            'tracked' => $tracked,
        ]);
    }
}
