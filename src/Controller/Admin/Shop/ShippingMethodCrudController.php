<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Addressing\Zone;
use App\Entity\Shop\Channel\Channel;
use App\Entity\Shop\Currency\Currency;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Shipping\ShippingCategory;
use App\Entity\Shop\Shipping\ShippingMethod;
use App\Entity\Shop\Taxonomy\Taxon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneMemberType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\CurrencyBundle\Form\Type\CurrencyType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ShippingBundle\Form\Type\CalculatorChoiceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryChoiceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodRuleCollectionType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodRuleType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

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
            ->addFormTheme('@EasyFields/form/translations_widget.html.twig')
            ;
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
            ->setFormTypeOption("zone_scope", Scope::SHIPPING);
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
        yield FormTypeField::new('calculator', 'sylius.form.shipping_method.calculator', CalculatorChoiceType::class);

        yield FormField::addPanel('sylius.form.shipping_method.rules')->setHelp("sylius.form.shipping_method.rules_help");
        yield CollectionField::new('rules', 'sylius.form.shipping_method.rules')
            ->setEntryType(ShippingMethodRuleType::class);

        yield FormField::addPanel('sylius.form.shipping_method.translations');
        yield TranslationField::new("translations", false, $fieldsConfig);
    }

}
