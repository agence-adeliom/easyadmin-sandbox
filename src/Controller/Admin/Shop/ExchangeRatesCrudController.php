<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use App\Entity\Shop\Currency\ExchangeRate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

class ExchangeRatesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExchangeRate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_exchange_rates")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.new_exchange_rate")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_exchange_rate")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.exchange_rate")
            ->setEntityLabelInSingular('sylius.ui.exchange_rate')
            ->setEntityLabelInPlural('sylius.ui.exchange_rates')

            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('sourceCurrency', "sylius.form.exchange_rate.source_currency")->setRequired(true)->setColumns(4);
        yield AssociationField::new('targetCurrency', "sylius.form.exchange_rate.target_currency")->setRequired(true)->setColumns(4);
        yield NumberField::new('ratio', "sylius.form.exchange_rate.ratio")
            ->setNumDecimals(5)
            ->setRequired(true)->setColumns(4)
            ->setFormTypeOption("invalid_message", 'sylius.exchange_rate.ratio.invalid')
            ->setFormTypeOption("scale", 5)
            ->setFormTypeOption("rounding_mode", \NumberFormatter::ROUND_HALFEVEN)
        ;
    }

}
