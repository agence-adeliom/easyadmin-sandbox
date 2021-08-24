<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Transformer;

use Adeliom\EasyShop\Component\Delivery\Pool as DeliveryPool;
use Adeliom\EasyShop\Component\Event\InvoiceTransformEvent;
use Adeliom\EasyShop\Component\Event\OrderTransformEvent;
use Adeliom\EasyShop\Component\Event\TransformerEvents;
use Adeliom\EasyShop\Component\Invoice\InvoiceElementInterface;
use Adeliom\EasyShop\Component\Invoice\InvoiceElementManagerInterface;
use Adeliom\EasyShop\Component\Invoice\InvoiceInterface;
use Adeliom\EasyShop\Component\Order\OrderElementInterface;
use Adeliom\EasyShop\Component\Order\OrderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


class InvoiceTransformer extends BaseTransformer
{
    /**
     * @var InvoiceElementManagerInterface
     */
    protected $invoiceElementManager;

    /**
     * @var DeliveryPool
     */
    protected $deliveryPool;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param InvoiceElementManagerInterface $invoiceElementManager Invoice element manager
     * @param DeliveryPool                   $deliveryPool          Delivery pool component
     */
    public function __construct(InvoiceElementManagerInterface $invoiceElementManager, DeliveryPool $deliveryPool, EventDispatcherInterface $eventDispatcher)
    {
        $this->invoiceElementManager = $invoiceElementManager;
        $this->deliveryPool = $deliveryPool;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Transforms an order into an invoice.
     */
    public function transformFromOrder(OrderInterface $order, InvoiceInterface $invoice): void
    {
        $event = new OrderTransformEvent($order);
        $this->eventDispatcher->dispatch($event, TransformerEvents::PRE_ORDER_TO_INVOICE_TRANSFORM);

        $invoice->setName($order->getBillingName());
        $invoice->setAddress1($order->getBillingAddress1());
        $invoice->setAddress2($order->getBillingAddress2());
        $invoice->setAddress3($order->getBillingAddress3());
        $invoice->setCity($order->getBillingCity());
        $invoice->setCountry($order->getBillingCountryCode());
        $invoice->setPostcode($order->getBillingPostcode());

        $invoice->setEmail($order->getBillingEmail());
        $invoice->setFax($order->getBillingFax());
        $invoice->setMobile($order->getBillingMobile());
        $invoice->setPhone($order->getBillingPhone());
        $invoice->setReference($order->getReference());

        $invoice->setCurrency($order->getCurrency());
        $invoice->setCustomer($order->getCustomer());
        $invoice->setTotalExcl($order->getTotalExcl());
        $invoice->setTotalInc($order->getTotalInc());

        $invoice->setPaymentMethod($order->getPaymentMethod());

        $invoice->setLocale($order->getLocale());

        foreach ($order->getOrderElements() as $orderElement) {
            $invoiceElement = $this->createInvoiceElementFromOrderElement($orderElement);
            $invoiceElement->setInvoice($invoice);
            $invoice->addInvoiceElement($invoiceElement);
        }

        if ($order->getDeliveryCost() > 0) {
            $this->addDelivery($invoice, $order);
        }

        $invoice->setStatus(InvoiceInterface::STATUS_OPEN);

        $event = new InvoiceTransformEvent($invoice);
        $this->eventDispatcher->dispatch($event, TransformerEvents::POST_ORDER_TO_INVOICE_TRANSFORM);
    }

    /**
     * Adds the delivery information from $order to $invoice.
     */
    protected function addDelivery(InvoiceInterface $invoice, OrderInterface $order): void
    {
        /** @var InvoiceElementInterface $invoiceElement */
        $invoiceElement = $this->invoiceElementManager->create();

        $invoiceElement->setQuantity(1);
        $invoiceElement->setPrice($order->getDeliveryCost());
        $invoiceElement->setUnitPriceExcl($order->getDeliveryCost());
        $invoiceElement->setUnitPriceInc($order->getDeliveryCost());
        $invoiceElement->setTotal($order->getDeliveryCost());
        $invoiceElement->setVatRate(0);

        $invoiceElement->setDesignation($this->deliveryPool->getMethod($order->getDeliveryMethod())->getName());
        $invoiceElement->setDescription($this->deliveryPool->getMethod($order->getDeliveryMethod())->getName());

        $invoiceElement->setInvoice($invoice);
        $invoice->addInvoiceElement($invoiceElement);
    }

    /**
     * Creates an InvoiceElement based on an OrderElement.
     *
     * @return \Adeliom\EasyShop\Component\Invoice\InvoiceElementInterface
     */
    protected function createInvoiceElementFromOrderElement(OrderElementInterface $orderElement)
    {
        $invoice = $this->invoiceElementManager->create();
        $invoice->setOrderElement($orderElement);
        $invoice->setDescription($orderElement->getDescription());
        $invoice->setDesignation($orderElement->getDesignation());
        $invoice->setPrice($orderElement->getPrice(true));
        $invoice->setUnitPriceExcl($orderElement->getUnitPrice(false));
        $invoice->setUnitPriceInc($orderElement->getUnitPrice(true));
        $invoice->setVatRate($orderElement->getVatRate());
        $invoice->setQuantity($orderElement->getQuantity());
        $invoice->setTotal($orderElement->getTotal(true));

        return $invoice;
    }
}
