<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Shipping\ShippingCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShippingCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShippingCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code');
        yield TextField::new('name');
        yield TextareaField::new('description');
    }

}
