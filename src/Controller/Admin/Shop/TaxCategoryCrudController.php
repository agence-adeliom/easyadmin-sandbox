<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Taxation\TaxCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TaxCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TaxCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code')
        ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''));

        yield TextField::new('name');

        yield TextareaField::new('description');
    }

}
