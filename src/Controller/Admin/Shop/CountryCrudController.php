<?php

namespace App\Controller\Admin\Shop;

use App\Entity\Shop\Addressing\Country;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;

class CountryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Country::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield CountryField::new('code');
        yield BooleanField::new('enabled');
        yield CollectionField::new("provinces")->setEntryType(ProvinceType::class);
    }

}
