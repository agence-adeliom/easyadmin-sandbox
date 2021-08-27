<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\ChoiceMaskField;
use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Customer\Customer;
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

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {

        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();

        yield FormField::addPanel("sylius.ui.customer_details");
        yield TextField::new("firstName", 'sylius.form.customer.first_name')->setRequired(true);
        yield TextField::new("lastName", 'sylius.form.customer.last_name')->setRequired(true);
        yield EmailField::new("email", 'sylius.form.customer.email')->setRequired(true);
        yield FormTypeField::new("group", 'sylius.form.customer.group', CustomerGroupChoiceType::class)
            ->setRequired(false)
            ->setFormTypeOption("attr", ["data-ea-widget" => "ea-autocomplete"])
        ;

        yield FormField::addPanel("sylius.ui.extra_information");
        yield ChoiceField::new("gender", 'sylius.form.customer.gender')
            ->setRequired(true)
            ->setFormType(GenderType::class)
            ->setChoices([
                'sylius.gender.unknown' => CustomerInterface::UNKNOWN_GENDER,
                'sylius.gender.male' => CustomerInterface::MALE_GENDER,
                'sylius.gender.female' => CustomerInterface::FEMALE_GENDER,
            ])
            ->setFormTypeOption('empty_data', CustomerInterface::UNKNOWN_GENDER);
        ;
        yield DateField::new("birthday", 'sylius.form.customer.birthday')
            ->setFormType(BirthdayType::class)
        ;
        yield TelephoneField::new("phoneNumber", 'sylius.form.customer.phone_number');
        yield BooleanField::new("subscribedToNewsletter", 'sylius.form.customer.subscribed_to_newsletter')->renderAsSwitch($pageName !== Crud::PAGE_INDEX);

        yield FormField::addPanel("sylius.ui.account_credentials");
        yield ChoiceMaskField::new("createUser", 'sylius.ui.customer_can_login_to_the_store')
            ->onlyOnForms()
            ->setRequired(true)
            ->setChoices(array_flip([
                "no" => 'sylius.ui.no_label',
                "yes" => 'sylius.ui.yes_label',
            ]))
            ->setFormTypeOption('data', $subject->getInstance() ? ($subject->getInstance()->getUser() ? "yes" : 'no') : "no")
            ->setFormTypeOption('required', false)
            ->setFormTypeOption('placeholder', null)
            ->setFormTypeOption('empty_data', "no")
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOption('mapped', false)
            ->setMap([
                "yes" => ["user"],
                "no" => [],
            ])
        ;
        yield FormTypeField::new("user", false, ShopUserType::class)
            ->setRequired(false)
            ->setFormTypeOption("label", false)
        ;
    }

    protected function processUploadedFiles(FormInterface $form): void
    {
        parent::processUploadedFiles($form);

        global $createUser;
        if($form->getData() instanceof Customer){
            $createUser = $form->get("createUser")->getData() == "yes";
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        global $createUser;

        parent::updateEntity($entityManager, $entityInstance);
    }

}
