<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use App\Entity\Shop\Customer\Customer;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Payment\Payment;
use App\Entity\Shop\Shipping\Shipment;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.payments")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.payment_details")
            ->setEntityLabelInSingular('sylius.ui.payment')
            ->setEntityLabelInPlural('sylius.ui.payments')
            ->showEntityActionsAsDropdown(false)
            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ->setDefaultSort([
                "createdAt" => "DESC"
            ])
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere("entity.state NOT LIKE 'cart'");

        return $qb;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add(DateTimeFilter::new('createdAt','sylius.ui.date'))
            ->add(ChoiceFilter::new('state','sylius.ui.state')->setChoices([
                'sylius.ui.'.Payment::STATE_COMPLETED => Payment::STATE_COMPLETED,
                'sylius.ui.'.Payment::STATE_CANCELLED => Payment::STATE_CANCELLED,
                'sylius.ui.'.Payment::STATE_AUTHORIZED => Payment::STATE_AUTHORIZED,
                'sylius.ui.'.Payment::STATE_CART => Payment::STATE_CART,
                'sylius.ui.'.Payment::STATE_UNKNOWN => Payment::STATE_UNKNOWN,
                'sylius.ui.'.Payment::STATE_REFUNDED => Payment::STATE_REFUNDED,
                'sylius.ui.'.Payment::STATE_PROCESSING => Payment::STATE_PROCESSING,
                'sylius.ui.'.Payment::STATE_FAILED => Payment::STATE_FAILED,
                'sylius.ui.'.Payment::STATE_NEW => Payment::STATE_NEW,
            ]))
        ;

        return $filters;
    }

    public function configureActions(Actions $actions): Actions
    {

        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        $actions->remove(Crud::PAGE_DETAIL, Action::DELETE);
        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);


        $viewOrder = Action::new('viewOrder', 'sylius.ui.view_order')->addCssClass('text-primary')
            ->linkToCrudAction("showOrder");

        $actions->add(Crud::PAGE_INDEX, $viewOrder);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('createdAt', 'sylius.ui.created_at');
        yield TextField::new('order.channel', 'sylius.ui.channel');
        yield TextField::new('order.number', 'sylius.ui.code')->formatValue(function ($value, $entity){
            if($value){
                return "#" . $value;
            }
        });
        yield TextField::new('order.customer', 'sylius.ui.customer')->formatValue(function ($value, Payment $entity){
            if($value){
                return '<strong>' . $entity->getOrder()->getCustomer()->getFullName() . "</strong><br>" . $entity->getOrder()->getCustomer()->getEmail();
            }
        });
        yield NumberField::new('amount', 'sylius.ui.amount')->formatValue(function ($value, Payment $entity){
            $formatter = new \NumberFormatter($entity->getOrder()->getLocaleCode(), \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($entity->getAmount() / 100, $entity->getCurrencyCode());
        })->setCssClass('text-md-right');

        yield ChoiceField::new('state', 'sylius.ui.state')
            ->setChoices([
                'sylius.ui.'.Payment::STATE_COMPLETED => Payment::STATE_COMPLETED,
                'sylius.ui.'.Payment::STATE_CANCELLED => Payment::STATE_CANCELLED,
                'sylius.ui.'.Payment::STATE_AUTHORIZED => Payment::STATE_AUTHORIZED,
                'sylius.ui.'.Payment::STATE_CART => Payment::STATE_CART,
                'sylius.ui.'.Payment::STATE_UNKNOWN => Payment::STATE_UNKNOWN,
                'sylius.ui.'.Payment::STATE_REFUNDED => Payment::STATE_REFUNDED,
                'sylius.ui.'.Payment::STATE_PROCESSING => Payment::STATE_PROCESSING,
                'sylius.ui.'.Payment::STATE_FAILED => Payment::STATE_FAILED,
                'sylius.ui.'.Payment::STATE_NEW => Payment::STATE_NEW,
            ])->setTemplatePath('@EasyShop/crud/Common/Label/paymentState.html.twig')
        ;
    }

    public function showOrder(AdminContext $context)
    {
        /** @var ParameterBagInterface $bag */
        $bag = $this->get(ParameterBagInterface::class);

        $order = $context->getEntity()->getInstance()->getOrder();
        $crud = $context->getCrudControllers()->findCrudFqcnByEntityFqcn($bag->get('sylius.model.order.class'));

        return $this->redirect(
            $this->get(AdminUrlGenerator::class)
                ->setController($crud)
                ->setAction(Action::DETAIL)
                ->set(EA::ENTITY_ID, $order->getId())
                ->generateUrl()
        );
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            ParameterBagInterface::class => '?'.ParameterBagInterface::class,
        ]);
    }
}
