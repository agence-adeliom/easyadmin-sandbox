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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\CoreBundle\Form\Type\User\ShopUserType;
use Sylius\Bundle\CustomerBundle\Form\Type\CustomerGroupChoiceType;
use Sylius\Bundle\CustomerBundle\Form\Type\GenderType;
use Sylius\Bundle\UserBundle\Factory\UserWithEncoderFactory;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormInterface;

class CustomerCrudController extends AbstractCrudController
{
    /**
     * @var FactoryInterface
     */
    protected $customerFactory;
    /**
     * @var FactoryInterface|UserWithEncoderFactory
     */
    protected $userFactory;
    public function __construct(FactoryInterface $customerFactory, FactoryInterface $userFactory, ParameterBag $bag)
    {
        $this->customerFactory = $customerFactory;
        $this->userFactory = $userFactory;
    }

    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $customer = $this->customerFactory->createNew();
        $customer->setUser($this->userFactory->createNew());
        return $customer;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyShop/SyliusFormTheme.html.twig')
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')

            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_customers")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.new_customer")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_customer")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.currency")
            ->setEntityLabelInSingular('sylius.ui.customer')
            ->setEntityLabelInPlural('sylius.ui.customers')

            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
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
        yield FormTypeField::new("group", 'sylius.form.customer.group', CustomerGroupChoiceType::class)->hideOnIndex()
            ->setRequired(false)
            ->setFormTypeOption("attr", ["data-ea-widget" => "ea-autocomplete"])
        ;

        yield FormField::addPanel("sylius.ui.extra_information");
        yield ChoiceField::new("gender", 'sylius.form.customer.gender')->hideOnIndex()
            ->setRequired(true)
            ->setFormType(GenderType::class)
            ->setChoices([
                'sylius.gender.unknown' => CustomerInterface::UNKNOWN_GENDER,
                'sylius.gender.male' => CustomerInterface::MALE_GENDER,
                'sylius.gender.female' => CustomerInterface::FEMALE_GENDER,
            ])
            ->setFormTypeOption('empty_data', CustomerInterface::UNKNOWN_GENDER);
        ;
        yield DateField::new("birthday", 'sylius.form.customer.birthday')->hideOnIndex()
            ->setFormType(BirthdayType::class)
        ;
        yield TelephoneField::new("phoneNumber", 'sylius.form.customer.phone_number')->hideOnIndex();
        yield BooleanField::new("subscribedToNewsletter", 'sylius.form.customer.subscribed_to_newsletter')->hideOnIndex()->renderAsSwitch(in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW]));

        yield FormField::addPanel("sylius.ui.account_credentials");
        yield ChoiceMaskField::new("createUser", 'sylius.ui.customer_can_login_to_the_store')->hideOnIndex()
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
        yield FormTypeField::new("user", false, ShopUserType::class)->hideOnIndex()
            ->setRequired(false)
            ->setFormTypeOption("label", false)
        ;

        yield DateTimeField::new('user.createdAt', 'sylius.ui.registration_date')->onlyOnIndex();
        yield BooleanField::new('user.enabled', 'sylius.form.user.enabled')->renderAsSwitch(false)->onlyOnIndex();
        yield BooleanField::new('user.verifiedAt', 'sylius.form.user.verified')->renderAsSwitch(false)->onlyOnIndex();
    }

    protected function processUploadedFiles(FormInterface $form): void
    {
        parent::processUploadedFiles($form);
        global $createUser;
        $createUser = false;
        if($form->getData() instanceof Customer){
            $createUser = $form->get("createUser")->getData() == "yes";
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        global $createUser;
        if(!$createUser){
            if($entityInstance->getUser() && $entityInstance->getUser()->getId()){
                $user = $entityInstance->getUser();
                $user->setEnabled(false);
                $entityInstance->setUser($user);
            }else{
                $entityInstance->setUser(null);
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

}
