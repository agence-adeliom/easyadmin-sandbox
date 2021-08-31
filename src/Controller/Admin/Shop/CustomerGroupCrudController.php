<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Customer\Customer;
use App\Entity\Shop\Customer\CustomerGroup;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\CoreBundle\Form\Type\User\ShopUserType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerGroupChoiceType;
use Sylius\Bundle\CustomerBundle\Form\Type\GenderType;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormInterface;

class CustomerGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CustomerGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_customer_groups")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.customer_groups")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.customer_groups")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.customer_groups")
            ->setEntityLabelInSingular('sylius.ui.customer_groups')
            ->setEntityLabelInPlural('sylius.ui.customer_groups')

            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new("code", 'sylius.ui.code')->setRequired(true)
            ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''));
        yield TextField::new("name", 'sylius.form.customer_group.name')->setRequired(true);

    }

}
