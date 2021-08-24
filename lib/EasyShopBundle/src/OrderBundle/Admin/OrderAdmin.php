<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Adeliom\EasyShop\AdminBundle\Admin\AbstractAdmin;
use Adeliom\EasyShop\AdminBundle\Admin\AdminInterface;
use Adeliom\EasyShop\AdminBundle\Datagrid\DatagridMapper;
use Adeliom\EasyShop\AdminBundle\Datagrid\ListMapper;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\AdminBundle\Form\Type\ModelListType;
use Adeliom\EasyShop\AdminBundle\Route\RouteCollection;
use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Currency\CurrencyFormType;
use Adeliom\EasyShop\Component\Invoice\InvoiceManagerInterface;
use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\Form\Type\DatePickerType;
use Adeliom\EasyShop\OrderBundle\Form\Type\OrderStatusType;
use Adeliom\EasyShop\PaymentBundle\Form\Type\PaymentTransactionStatusType;
use Adeliom\EasyShop\ProductBundle\Form\Type\ProductDeliveryStatusType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderAdmin extends AbstractAdmin
{
    /**
     * @var CurrencyDetectorInterface
     */
    protected $currencyDetector;

    /**
     * @var InvoiceManagerInterface
     */
    protected $invoiceManager;

    /**
     * @var OrderManagerInterface
     */
    protected $orderManager;

    public function setCurrencyDetector(CurrencyDetectorInterface $currencyDetector): void
    {
        $this->currencyDetector = $currencyDetector;
    }

    public function setInvoiceManager(InvoiceManagerInterface $invoiceManager): void
    {
        $this->invoiceManager = $invoiceManager;
    }

    public function setOrderManager(OrderManagerInterface $orderManager): void
    {
        $this->orderManager = $orderManager;
    }

    public function configure(): void
    {
        $this->parentAssociationMapping = 'customer';
        $this->setTranslationDomain('SonataOrderBundle');
    }

    public function configureFormFields(FormMapper $formMapper): void
    {
        // define group zoning
        $formMapper
             ->with('order.form.group_main_label', ['class' => 'col-md-12'])->end()
             ->with('order.form.group_billing_label', ['class' => 'col-md-6'])->end()
             ->with('order.form.group_shipping_label', ['class' => 'col-md-6'])->end();

        if (!$this->isChild()) {
            $formMapper
                ->with('order.form.group_main_label')
                    ->add('customer', ModelListType::class)
                ->end();
        }

        $formMapper
            ->with('order.form.group_main_label')
                ->add('currency', CurrencyFormType::class)
                ->add('locale', LocaleType::class)
                ->add('status', OrderStatusType::class, ['translation_domain' => 'SonataOrderBundle'])
                ->add('paymentStatus', PaymentTransactionStatusType::class, ['translation_domain' => 'SonataPaymentBundle'])
                ->add('deliveryStatus', ProductDeliveryStatusType::class, ['translation_domain' => 'SonataDeliveryBundle'])
                ->add('validatedAt', DatePickerType::class, ['dp_side_by_side' => true])
            ->end()
            ->with('order.form.group_billing_label', ['collapsed' => true])
                ->add('billingName')
                ->add('billingAddress1')
                ->add('billingAddress2')
                ->add('billingAddress3')
                ->add('billingCity')
                ->add('billingPostcode')
                ->add('billingCountryCode', CountryType::class)
                ->add('billingFax')
                ->add('billingEmail')
                ->add('billingMobile')
            ->end()
            ->with('order.form.group_shipping_label', ['collapsed' => true])
                ->add('shippingName')
                ->add('shippingAddress1')
                ->add('shippingAddress2')
                ->add('shippingAddress3')
                ->add('shippingCity')
                ->add('shippingPostcode')
                ->add('shippingCountryCode', CountryType::class)
                ->add('shippingFax')
                ->add('shippingEmail')
                ->add('shippingMobile')
            ->end();
    }

    public function configureListFields(ListMapper $list): void
    {
        $currency = $this->currencyDetector->getCurrency()->getLabel();

        $list
            ->addIdentifier('reference');

        if (!$list->getAdmin()->isChild()) {
            $list->addIdentifier('customer');
        }

        $list
            ->add('status', TextType::class, [
                'template' => '@SonataOrder/OrderAdmin/list_status.html.twig',
            ])
            ->add('deliveryStatus', TextType::class, [
                'template' => '@SonataOrder/OrderAdmin/list_delivery_status.html.twig',
            ])
            ->add('paymentStatus', TextType::class, [
                'template' => '@SonataOrder/OrderAdmin/list_payment_status.html.twig',
            ])
            ->add('validatedAt')
            ->add('totalInc', CurrencyFormType::class, ['currency' => $currency]);
    }

    public function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('reference');

        if (!$this->isChild()) {
            $filter->add('customer.lastname');
        }

        $filter
            ->add('status', null, [], OrderStatusType::class, ['translation_domain' => $this->translationDomain])
            ->add('deliveryStatus', null, [], ProductDeliveryStatusType::class, ['translation_domain' => 'SonataDeliveryBundle'])
            ->add('paymentStatus', null, [], PaymentTransactionStatusType::class, ['translation_domain' => 'SonataPaymentBundle']);
    }

    public function configureRoutes(RouteCollection $collection): void
    {
        $collection->remove('create');
        $collection->add('generateInvoice');
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !\in_array($action, ['edit'], true)) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            'easy_shop.order.sidemenu.link_order_edit',
            ['uri' => $admin->generateUrl('edit', ['id' => $id])]
        );

        $menu->addChild(
            'easy_shop.order.sidemenu.link_order_elements_list',
            ['uri' => $admin->generateUrl('easy_shop.order.admin.order_element.list', ['id' => $id])]
        );

        $order = $this->orderManager->findOneBy(['id' => $id]);
        $invoice = $this->invoiceManager->findOneBy(['reference' => $order->getReference()]);

        if (null === $invoice) {
            $menu->addChild(
                'easy_shop.order.sidemenu.link_oRDER_TO_INVOICE_generate',
                ['uri' => $admin->generateUrl('generateInvoice', ['id' => $id])]
            );
        } else {
            $menu->addChild(
                'easy_shop.order.sidemenu.link_oRDER_TO_INVOICE_edit',
                ['uri' => $this->getConfigurationPool()->getAdminByAdminCode('easy_shop.invoice.admin.invoice')->generateUrl('edit', ['id' => $invoice->getId()])]
            );
        }
    }
}
