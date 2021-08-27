<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use App\Entity\Shop\Taxation\TaxCategory;
use App\Entity\Shop\Taxation\TaxRate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneChoiceType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCalculatorChoiceType;
use Sylius\Bundle\TaxationBundle\Form\Type\TaxCategoryChoiceType;
use Sylius\Component\Core\Model\Scope;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TaxRateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TaxRate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel("sylius.ui.general_info")->collapsible()->renderCollapsed(false);

        yield TextField::new('code')
            ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''))
            ->setColumns(6);

        yield TextField::new('name')
            ->setColumns(6);

        yield FormField::addPanel("sylius.ui.criteria")->collapsible()->renderCollapsed(false);

        yield FormTypeField::new('category', 'sylius.form.tax_rate.category', TaxCategoryChoiceType::class)
            ->setFormTypeOption("attr", ["data-ea-widget" => "ea-autocomplete"])
            ->setColumns(6)
            ->setRequired(true);

        yield FormTypeField::new('zone', 'sylius.form.address.zone', ZoneChoiceType::class)
            ->setFormTypeOptions(['zone_scope' => Scope::TAX, "attr" => ["data-ea-widget" => "ea-autocomplete"]])
            ->setColumns(6)
            ->setRequired(true);

        yield FormField::addPanel("sylius.ui.taxes")->collapsible()->renderCollapsed(false);

        yield FormTypeField::new('calculator', 'sylius.form.tax_rate.calculator', TaxCalculatorChoiceType::class)
            ->setFormTypeOption("attr", ["data-ea-widget" => "ea-autocomplete"]);

        yield PercentField::new('amount')
            ->setFormTypeOption("scale", 3);

        yield BooleanField::new('includedInPrice')->setLabel("sylius.form.tax_rate.included_in_price");
    }

}
