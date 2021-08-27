<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use App\Entity\Shop\Currency\ExchangeRate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class ExchangeRatesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExchangeRate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('sourceCurrency');
        yield AssociationField::new('targetCurrency');
        yield NumberField::new('ratio')->setNumDecimals(3);
    }

}
