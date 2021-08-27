<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use Adeliom\EasyShopBundle\Form\Type\ShippingBundle\ShippingMethodCalculatorType;
use Adeliom\EasyShopBundle\Form\Type\ShippingBundle\ShippingMethodRuleType;
use App\Entity\Shop\Shipping\ShippingMethod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneChoiceType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShippingMethodCrudController extends AbstractCrudController
{
    /** @var ServiceRegistryInterface */
    private $calculatorRegistry;

    /** @var FormTypeRegistryInterface */
    private $formTypeRegistry;

    public function __construct(ServiceRegistryInterface $calculatorRegistry, FormTypeRegistryInterface $formTypeRegistry)
    {
        $this->calculatorRegistry = $calculatorRegistry;
        $this->formTypeRegistry = $formTypeRegistry;
    }

    public static function getEntityFqcn(): string
    {
        return ShippingMethod::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $fieldsConfig = [
            'name' => [
                'field_type' => TextType::class,
                'required' => true,
            ],
            'description' => [
                'field_type' => TextareaType::class,
                'required' => true,
            ]
        ];
        yield TextField::new('code', 'sylius.ui.code');
        yield FormTypeField::new('zone', 'sylius.form.shipping_method.zone', ZoneChoiceType::class)
            ->setFormTypeOption("zone_scope", Scope::SHIPPING)->setRequired(true);
        yield NumberField::new('position', 'sylius.form.shipping_method.position')
            ->setNumDecimals(0);
        yield BooleanField::new('enabled', 'sylius.form.locale.enabled');
        yield FormTypeField::new('channels', 'sylius.form.shipping_method.channels', ChannelChoiceType::class)
            ->setFormTypeOption("multiple", true)
            ->setFormTypeOption("expanded", true);
        yield FormTypeField::new('taxCategory', 'sylius.form.shipping_method.tax_category', TaxCategoryChoiceType::class)
            ->setFormTypeOption("required", false)
            ->setFormTypeOption("placeholder", '---');
        yield FormTypeField::new('category', 'sylius.form.shipping_method.category', ShippingCategoryChoiceType::class);
        yield ChoiceField::new("categoryRequirement", 'sylius.form.shipping_method.category_requirement')
            ->setChoices([
                'sylius.form.shipping_method.match_none_category_requirement' => ShippingMethodInterface::CATEGORY_REQUIREMENT_MATCH_NONE,
                'sylius.form.shipping_method.match_any_category_requirement' => ShippingMethodInterface::CATEGORY_REQUIREMENT_MATCH_ANY,
                'sylius.form.shipping_method.match_all_category_requirement' => ShippingMethodInterface::CATEGORY_REQUIREMENT_MATCH_ALL,
            ])->renderExpanded();

        yield FormTypeField::new('calculator', 'sylius.form.shipping_method.calculator', ShippingMethodCalculatorType::class);


        yield FormField::addPanel('sylius.form.shipping_method.rules')->setHelp("sylius.form.shipping_method.rules_help");
        yield SortableCollectionField::new('rules', 'sylius.form.shipping_method.rules')
            ->setEntryType(ShippingMethodRuleType::class)->allowDrag(false);

        yield FormField::addPanel('sylius.form.shipping_method.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formOptions->set("allow_extra_fields", true);
        return parent::createNewFormBuilder($entityDto, $formOptions, $context);
    }

}
