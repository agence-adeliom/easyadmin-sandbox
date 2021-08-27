<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Product\ProductAttribute;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sylius\Bundle\AttributeBundle\Form\Type\AttributeTypeChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductAttributeCrudController extends AbstractCrudController
{
    /** @var FormTypeRegistryInterface */
    protected $formTypeRegistry;
    private AdminUrlGenerator $crudUrlGenerator;
    private ParameterBagInterface $parameterBag;

    public function __construct(AdminUrlGenerator $crudUrlGenerator, ParameterBagInterface $parameterBag, FormTypeRegistryInterface $formTypeRegistry)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->parameterBag = $parameterBag;
        $this->formTypeRegistry = $formTypeRegistry;
    }

    public static function getEntityFqcn(): string
    {
        return ProductAttribute::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $url = $this->crudUrlGenerator->setController(self::class)->setAction(Action::NEW);

        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);

        foreach (array_reverse($this->parameterBag->get("sylius.attribute.attribute_types")) as $type => $name) {
            $actionType = Action::new($type, $name)
                ->linkToUrl((clone $url)->set("attributeType", $type)->generateUrl())
                ->createAsGlobalAction()
                ->setCssClass("btn btn-primary");
            $actions->add(Crud::PAGE_INDEX, $actionType);
        }

        return $actions;
    }

    public function new(AdminContext $context)
    {
        global $attributeType;
        $attributeType = $context->getRequest()->query->get("attributeType");
        return parent::new($context);
    }

    public function createEntity(string $entityFqcn)
    {
        global $attributeType;
        /** @var ProductAttributeInterface $entity */
        $entity = new $entityFqcn();
        $entity->setType($attributeType);
        return $entity;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();
        $attribute = $subject->getInstance();

        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
            ]
        ];

        yield TextField::new('code', 'sylius.ui.code')->setColumns(4);
        yield IntegerField::new('position', 'sylius.form.product_attribute.position')
            ->setRequired(false)
            ->setFormTypeOption('invalid_message', 'sylius.product_attribute.invalid')
            ->setColumns(4);
        yield FormTypeField::new('type', 'sylius.form.attribute.type', AttributeTypeChoiceType::class)
            ->setFormTypeOption("disabled", true)
            ->setColumns(4);
        yield BooleanField::new('translatable', 'sylius.form.attribute.translatable');

        if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            if (($attribute instanceof AttributeInterface) && $this->formTypeRegistry->has($attribute->getType(), 'configuration')) {
                yield FormTypeField::new('configuration', 'sylius.form.attribute_type.configuration', $this->formTypeRegistry->get($attribute->getType(), 'configuration'))
                    ->setFormTypeOption("auto_initialize", false);
            }
        }

        yield FormField::addPanel('sylius.form.attribute.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

}
