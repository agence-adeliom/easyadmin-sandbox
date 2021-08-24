<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Currency\Currency;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;

class CurrencyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Currency::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield CurrencyField::new('code')->showName()->showCode()->showSymbol();
    }

}
