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
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionChoiceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Adeliom\EasyShopBundle\Form\Type\ProductBundle\ChannelCollectionType;

class ProductCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $crudUrlGenerator;
    private AdminContextProvider $adminContextProvider;
    private TranslatorInterface $translator;
    private ProductFactoryInterface $productFactory;

    public function __construct(
        AdminUrlGenerator       $crudUrlGenerator,
        AdminContextProvider    $adminContextProvider,
        TranslatorInterface     $translator,
        ProductFactoryInterface $productFactory
    )
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->adminContextProvider = $adminContextProvider;
        $this->translator = $translator;
        $this->productFactory = $productFactory;
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
            ->addFormTheme('@EasyMedia/form/easy-media.html.twig')
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ->addFormTheme('@EasyCommon/crud/custom_panel.html.twig')
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')

            ->showEntityActionsAsDropdown();
    }

    public function configureActions(Actions $actions): Actions
    {
        $url = $this->crudUrlGenerator->setController(self::class)->setAction(Action::NEW);
        $actions = parent::configureActions($actions);
        $addTypes = [
            'simple_product' => $this->translator->trans('simple_product'),
            'configurable_product' => $this->translator->trans('configurable_product'),
        ];
        foreach ($addTypes as $key => $label) {
            $newAdd = Action::new($key, 'Créer ' . $label)->linkToUrl((clone $url)->set("productType", $key))->createAsGlobalAction()->setCssClass("btn btn-primary");
            $actions->add(Crud::PAGE_INDEX, $newAdd);
        }
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);

        return $actions;
    }

    public function new(AdminContext $context)
    {
        global $productType;
        $productType = $context->getRequest()->query->get("productType");
        return parent::new($context);
    }

    public function createEntity(string $entityFqcn)
    {
        return $this->productFactory->createWithVariant();
    }

    public function configureFields(string $pageName): iterable
    {

        if ($this->productIsSimple()) {

            yield FormField::addPanel("sylius.ui.details")->collapsible()->renderCollapsed(false);

            yield TextField::new('code')->setLabel('sylius.ui.code');
            yield BooleanField::new('enabled')->setLabel('sylius.ui.enabled')->renderAsSwitch(in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT]));
            yield BooleanField::new('variant.shippingRequired')->setLabel('sylius.form.variant.shipping_required');


            yield FormTypeField::new('channels', 'sylius.form.product.channels', ChannelChoiceType::class)
                ->setFormTypeOption("multiple", true)
                ->setFormTypeOption("expanded", true);

            yield FormField::addPanel("sylius.ui.inventory")->collapsible()->renderCollapsed();

            yield NumberField::new('variant.onHand')->setLabel('sylius.form.variant.on_hand');
            yield BooleanField::new('variant.tracked')->setLabel('sylius.form.variant.tracked')->setHelp('sylius.form.variant.tracked_help');

            yield FormField::addPanel("sylius.ui.shipping")->collapsible()->renderCollapsed();

            yield FormTypeField::new('variant.shippingCategory', 'sylius.form.product_variant.shipping_category', ShippingCategoryChoiceType::class)
                ->setFormTypeOptions(["attr" => ["data-ea-widget" => "ea-autocomplete"]]);
            yield NumberField::new('variant.width')->setLabel('sylius.form.variant.width');
            yield NumberField::new('variant.height')->setLabel('sylius.form.variant.height');
            yield NumberField::new('variant.depth')->setLabel('sylius.form.variant.depth');
            yield NumberField::new('variant.weight')->setLabel('sylius.form.variant.weight');

            yield FormField::addPanel("sylius.ui.taxes")->collapsible()->renderCollapsed();

            yield FormTypeField::new('variant.taxCategory', 'sylius.form.product_variant.tax_category', TaxCategoryChoiceType::class)
                ->setFormTypeOptions(["attr" => ["data-ea-widget" => "ea-autocomplete"]]);

            yield FormField::addPanel("sylius.ui.pricing")->collapsible()->renderCollapsed();

            yield FormTypeField::new('virtualVariantChannelPricing', 'sylius.form.variant.price', ChannelCollectionType::class)
                ->setFormTypeOptions([]);

//            yield FormField::addPanel("Variant")->collapsible()->renderCollapsed();
//            yield FormTypeField::new('variant', '', ProductVariantType::class)
//                ->setFormTypeOptions([
//                    'property_path' => 'variants[0]',
//                    'constraints' => [
//                        new Valid(),
//                    ]
//                ])
//                ->setTemplatePath("@EasyShop/form/admin_product.html.twig")
        } else {

            yield FormField::addPanel("sylius.ui.details")->collapsible()->renderCollapsed();

            yield TextField::new('code');
            yield BooleanField::new('enabled');

            //
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

        yield FormField::addPanel("Attributes")->collapsible()->renderCollapsed();
        yield ProductAttributesField::new("attributes", false)
            ->hideOnIndex();

        yield FormField::addPanel("Associations")->collapsible()->renderCollapsed();
        yield ProductAssociationsField::new("associations", "test")
            ->hideOnIndex();

//        yield FormTypeField::new("associations", false, ProductAssociationsType::class)
//            ->setTemplatePath("@EasyShop/field/product/associations.html.twig")
//            ->hideOnIndex();

//        yield FormTypeField::new("associations", false, Prod::class);
        //yield FormTypeField::new("associations", false, \Sylius\Bundle\ProductBundle\Form\Type\ProductAssociationsType::class);
        yield FormField::addPanel("Contenus")->collapsible()->renderCollapsed();
        yield TranslationField::new("translations", 'Contenus', $fieldsConfig);

    }

    protected function productIsSimple(): bool
    {
        global $productType;

        /**
         * @var Product $entity
         */
        $entity = $this->adminContextProvider->getContext()->getEntity()->getInstance();

        if (!empty($productType) && $productType === 'simple_product') {
            return true;
        } elseif (!empty($entity) && $entity->getId() && $entity->isSimple()) {
            return true;
        } else {
            return false;
        }

    }
}
