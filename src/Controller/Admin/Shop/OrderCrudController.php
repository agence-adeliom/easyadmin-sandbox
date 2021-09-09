<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use App\Entity\Shop\Customer\Customer;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Order\Order;
use App\Entity\Shop\Payment\Payment;
use App\Entity\Shop\Shipping\Shipment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ActionFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FormFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\PaginatorFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityUpdater;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Provider\FieldProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use SM\Factory\Factory;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ShipmentRepository;
use Sylius\Bundle\CoreBundle\Form\Extension\OrderTypeExtension;
use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderRepository;
use Sylius\Bundle\OrderBundle\Form\Type\OrderType;
use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\OrderShippingStates;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\Component\Core\Repository\ShipmentRepositoryInterface;
use Sylius\Component\Mailer\Sender\Sender;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Shipping\ShipmentTransitions;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.orders")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.new_order")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_order")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.order_details")
            ->setEntityLabelInSingular('sylius.ui.order')
            ->setEntityLabelInPlural('sylius.ui.orders')
            ->showEntityActionsAsDropdown(false)
            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ->setDefaultSort([
                "createdAt" => "DESC"
            ])
            ->overrideTemplate('crud/detail', '@EasyShop/crud/order/detail.html.twig')
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere("entity.state NOT LIKE 'cart'");
        return $qb;
    }

    public function configureActions(Actions $actions): Actions
    {

        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        $actions->remove(Crud::PAGE_DETAIL, Action::DELETE);
        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);

        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add(DateTimeFilter::new('createdAt','sylius.ui.date'))
            ->add(EntityFilter::new('channel','sylius.ui.channel'))
            ->add(EntityFilter::new('customer','sylius.ui.customer'))
            ->add(ChoiceFilter::new('state','sylius.ui.state')->setChoices([
                'sylius.ui.'.Order::STATE_CART => Order::STATE_CART,
                'sylius.ui.'.Order::STATE_CANCELLED => Order::STATE_CANCELLED,
                'sylius.ui.'.Order::STATE_FULFILLED => Order::STATE_FULFILLED,
                'sylius.ui.'.Order::STATE_NEW => Order::STATE_NEW
//                'sylius.ui.'.OrderCheckoutStates::STATE_CART => OrderCheckoutStates::STATE_CART,
//                'sylius.ui.'.OrderCheckoutStates::STATE_CART => OrderCheckoutStates::STATE_CART,
//                'sylius.ui.'.OrderCheckoutStates::STATE_COMPLETED => OrderCheckoutStates::STATE_COMPLETED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_ADDRESSED => OrderCheckoutStates::STATE_ADDRESSED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_PAYMENT_SELECTED => OrderCheckoutStates::STATE_PAYMENT_SELECTED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_PAYMENT_SKIPPED => OrderCheckoutStates::STATE_PAYMENT_SKIPPED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_SHIPPING_SELECTED => OrderCheckoutStates::STATE_SHIPPING_SELECTED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_SHIPPING_SKIPPED => OrderCheckoutStates::STATE_SHIPPING_SKIPPED,
            ]))
            ->add(ChoiceFilter::new('paymentState','sylius.ui.payment_state')->setChoices([
                'sylius.ui.'.OrderPaymentStates::STATE_AUTHORIZED => OrderPaymentStates::STATE_AUTHORIZED,
                'sylius.ui.'.OrderPaymentStates::STATE_AWAITING_PAYMENT => OrderPaymentStates::STATE_AWAITING_PAYMENT,
                'sylius.ui.'.OrderPaymentStates::STATE_CANCELLED => OrderPaymentStates::STATE_CANCELLED,
                'sylius.ui.'.OrderPaymentStates::STATE_PAID => OrderPaymentStates::STATE_PAID,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED => OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_PAID => OrderPaymentStates::STATE_PARTIALLY_PAID,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_REFUNDED => OrderPaymentStates::STATE_PARTIALLY_REFUNDED,
                'sylius.ui.'.OrderPaymentStates::STATE_REFUNDED => OrderPaymentStates::STATE_REFUNDED,
            ]))
            ->add(ChoiceFilter::new('shippingState','sylius.ui.shipping_state')->setChoices([
                'sylius.ui.'.OrderShippingStates::STATE_CART => OrderShippingStates::STATE_CART,
                'sylius.ui.'.OrderShippingStates::STATE_CANCELLED => OrderShippingStates::STATE_CANCELLED,
                'sylius.ui.'.OrderShippingStates::STATE_PARTIALLY_SHIPPED => OrderShippingStates::STATE_PARTIALLY_SHIPPED,
                'sylius.ui.'.OrderShippingStates::STATE_READY => OrderShippingStates::STATE_READY,
                'sylius.ui.'.OrderShippingStates::STATE_SHIPPED => OrderShippingStates::STATE_SHIPPED,
            ]))
        ;

        return $filters;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('createdAt', 'sylius.ui.date');
        yield TextField::new('channel', 'sylius.ui.channel');
        yield TextField::new('number', 'sylius.ui.code')->formatValue(function ($value, $entity){
            if($value){
                return "#" . $value;
            }
        });
        yield TextField::new('customer', 'sylius.ui.customer')->formatValue(function ($value, Order $entity){
            if($value){
                return '<strong>' . $entity->getCustomer()->getFullName() . "</strong><br>" . $entity->getCustomer()->getEmail();
            }
        });
        yield ChoiceField::new('state', 'sylius.ui.state')
            ->setChoices([
                'sylius.ui.'.Order::STATE_CART => Order::STATE_CART,
                'sylius.ui.'.Order::STATE_CANCELLED => Order::STATE_CANCELLED,
                'sylius.ui.'.Order::STATE_FULFILLED => Order::STATE_FULFILLED,
                'sylius.ui.'.Order::STATE_NEW => Order::STATE_NEW
//                'sylius.ui.'.OrderCheckoutStates::STATE_CART => OrderCheckoutStates::STATE_CART,
//                'sylius.ui.'.OrderCheckoutStates::STATE_CART => OrderCheckoutStates::STATE_CART,
//                'sylius.ui.'.OrderCheckoutStates::STATE_COMPLETED => OrderCheckoutStates::STATE_COMPLETED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_ADDRESSED => OrderCheckoutStates::STATE_ADDRESSED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_PAYMENT_SELECTED => OrderCheckoutStates::STATE_PAYMENT_SELECTED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_PAYMENT_SKIPPED => OrderCheckoutStates::STATE_PAYMENT_SKIPPED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_SHIPPING_SELECTED => OrderCheckoutStates::STATE_SHIPPING_SELECTED,
//                'sylius.ui.'.OrderCheckoutStates::STATE_SHIPPING_SKIPPED => OrderCheckoutStates::STATE_SHIPPING_SKIPPED,
            ]);
        yield ChoiceField::new('paymentState', 'sylius.ui.payment_state')
            ->setChoices([
                'sylius.ui.'.OrderPaymentStates::STATE_AUTHORIZED => OrderPaymentStates::STATE_AUTHORIZED,
                'sylius.ui.'.OrderPaymentStates::STATE_AWAITING_PAYMENT => OrderPaymentStates::STATE_AWAITING_PAYMENT,
                'sylius.ui.'.OrderPaymentStates::STATE_CANCELLED => OrderPaymentStates::STATE_CANCELLED,
                'sylius.ui.'.OrderPaymentStates::STATE_PAID => OrderPaymentStates::STATE_PAID,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED => OrderPaymentStates::STATE_PARTIALLY_AUTHORIZED,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_PAID => OrderPaymentStates::STATE_PARTIALLY_PAID,
                'sylius.ui.'.OrderPaymentStates::STATE_PARTIALLY_REFUNDED => OrderPaymentStates::STATE_PARTIALLY_REFUNDED,
                'sylius.ui.'.OrderPaymentStates::STATE_REFUNDED => OrderPaymentStates::STATE_REFUNDED,
            ]);
        yield ChoiceField::new('shippingState', 'sylius.ui.shipping_state')
            ->setChoices([
                'sylius.ui.'.OrderShippingStates::STATE_CART => OrderShippingStates::STATE_CART,
                'sylius.ui.'.OrderShippingStates::STATE_CANCELLED => OrderShippingStates::STATE_CANCELLED,
                'sylius.ui.'.OrderShippingStates::STATE_PARTIALLY_SHIPPED => OrderShippingStates::STATE_PARTIALLY_SHIPPED,
                'sylius.ui.'.OrderShippingStates::STATE_READY => OrderShippingStates::STATE_READY,
                'sylius.ui.'.OrderShippingStates::STATE_SHIPPED => OrderShippingStates::STATE_SHIPPED,
            ]);
        yield NumberField::new('total', 'sylius.ui.total')->formatValue(function ($value, Order $entity){
            $formatter = new \NumberFormatter($entity->getLocaleCode(), \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($entity->getTotal() / 100, $entity->getCurrencyCode());
        })->setCssClass('text-md-right');
        yield CurrencyField::new('currencyCode', 'sylius.ui.currency');
    }

    public function showCustomer(AdminContext $context)
    {
        $customerCrud = $context->getCrudControllers()->findCrudFqcnByEntityFqcn($this->get(ParameterBagInterface::class)->get('sylius.model.customer.class'));
        return $this->redirect(
            $this->get(AdminUrlGenerator::class)
                ->setController($customerCrud)
                ->setAction(Action::DETAIL)
                ->set(EA::ENTITY_ID, $context->getRequest()->query->get("customerId"))
                ->generateUrl()
        );
    }

    public function showShipment(AdminContext $context)
    {
        $shipmentCrud = $context->getCrudControllers()->findCrudFqcnByEntityFqcn($this->get(ParameterBagInterface::class)->get('sylius.model.shipment.class'));
        return $this->redirect(
            $this->get(AdminUrlGenerator::class)
                ->setController($shipmentCrud)
                ->setAction(Action::DETAIL)
                ->set(EA::ENTITY_ID, $context->getRequest()->query->get("shipmentId"))
                ->generateUrl()
        );
    }

    public function paymentComplete(AdminContext $context){
        $request = $context->getRequest();
        $paymentId = $request->query->get('payment_id');

        /** @var PaymentInterface|null $payment */
        $payment = $this->get('sylius.repository.payment')->find($paymentId);
        if ($payment === null) {
            throw new NotFoundHttpException(sprintf('The payment with id %s has not been found', $paymentId));
        }

        $em = $this->getDoctrine()->getManager();
        $sm = $this->get(Factory::class)->get($payment, "sylius_payment");
        if($sm->apply(PaymentTransitions::TRANSITION_COMPLETE)) {
            $em->persist($payment);
            $em->flush();

            $this->addFlash(
                'success',
                'sylius.shipment.completed'
            );
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function paymentRefund(AdminContext $context){
        $request = $context->getRequest();
        $paymentId = $request->query->get('payment_id');

        /** @var PaymentInterface|null $payment */
        $payment = $this->get('sylius.repository.payment')->find($paymentId);
        if ($payment === null) {
            throw new NotFoundHttpException(sprintf('The payment with id %s has not been found', $paymentId));
        }

        $em = $this->getDoctrine()->getManager();
        $sm = $this->get(Factory::class)->get($payment, "sylius_payment");
        if($sm->apply(PaymentTransitions::TRANSITION_REFUND)) {
            $em->persist($payment);
            $em->flush();

            $this->addFlash(
                'success',
                'sylius.shipment.completed'
            );
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function shipmentTracking(AdminContext $context)
    {

        $request = $context->getRequest();
        /** @var OrderInterface|null $order */

        $order = $context->getEntity()->getInstance();
        $shipmentId = $request->query->get('shipment_id');

        /** @var ShipmentInterface|null $shipment */
        $shipment = $this->get('sylius.repository.shipment')->find($shipmentId);
        if ($shipment === null) {
            throw new NotFoundHttpException(sprintf('The shipment with id %s has not been found', $shipmentId));
        }
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get(Factory::class)->get($shipment, "sylius_shipment");
        if($request->get('tracking')){
            if($sm->apply(ShipmentTransitions::TRANSITION_SHIP)) {
                $shipment->setTracking($request->get('tracking'));
                $em->persist($shipment);
                $em->flush();

                $this->get(Sender::class)->send(
                    Emails::SHIPMENT_CONFIRMATION,
                    [$order->getCustomer()->getEmail()],
                    [
                        'shipment' => $shipment,
                        'order' => $order,
                        'channel' => $order->getChannel(),
                        'localeCode' => $order->getLocaleCode(),
                    ]
                );
                $this->addFlash(
                    'success',
                    'sylius.shipment.shipped'
                );
            }
        }

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function resendOrderConfirmationEmail(AdminContext $context)
    {
        $request = $context->getRequest();
        /** @var OrderInterface|null $order */
        $order = $context->getEntity()->getInstance();

        if (!$this->get(CsrfTokenManager::class)->isTokenValid(new CsrfToken($order->getId(), (string) $request->query->get('_csrf_token')))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        $this->get(Sender::class)->send(
            Emails::ORDER_CONFIRMATION_RESENT,
            [$order->getCustomer()->getEmail()],
            [
                'order' => $order,
                'channel' => $order->getChannel(),
                'localeCode' => $order->getLocaleCode(),
            ]
        );

        $this->addFlash(
            'success',
            'sylius.email.order_confirmation_resent'
        );

        return new RedirectResponse($request->headers->get('referer'));
    }

    public function resendShipmentConfirmationEmail(AdminContext $context)
    {
        $request = $context->getRequest();

        /** @var OrderInterface|null $order */
        $order = $context->getEntity()->getInstance();
        $shipmentId = $request->query->get('shipment_id');

        if (!$this->get(CsrfTokenManager::class)->isTokenValid(new CsrfToken($shipmentId, (string) $request->query->get('_csrf_token')))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        /** @var ShipmentInterface|null $shipment */
        $shipment = $this->get('sylius.repository.shipment')->find($shipmentId);
        if ($shipment === null) {
            throw new NotFoundHttpException(sprintf('The shipment with id %s has not been found', $shipmentId));
        }

        $this->get(Sender::class)->send(
            Emails::SHIPMENT_CONFIRMATION,
            [$order->getCustomer()->getEmail()],
            [
                'shipment' => $shipment,
                'order' => $order,
                'channel' => $order->getChannel(),
                'localeCode' => $order->getLocaleCode(),
            ]
        );

        $this->addFlash(
            'success',
            'sylius.email.shipment_confirmation_resent'
        );

        return new RedirectResponse($request->headers->get('referer'));
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            OrderRepository::class => '?'.OrderRepositoryInterface::class,
            'sylius.repository.shipment' => '?'.ShipmentRepositoryInterface::class,
            'sylius.repository.payment' => '?'.PaymentRepositoryInterface::class,
            Factory::class => '?'.FactoryInterface::class,
            Sender::class => '?'.SenderInterface::class,
            CsrfTokenManager::class => '?'.CsrfTokenManagerInterface::class,
            ParameterBagInterface::class => '?'.ParameterBagInterface::class,
        ]);
    }

}
