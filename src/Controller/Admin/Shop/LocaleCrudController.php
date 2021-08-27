<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Locale\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;

class LocaleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Locale::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield LocaleField::new('code')->showName()->showCode();
    }

}
