<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PaymentBundle\Consumer;

use Adeliom\EasyShop\Component\Order\OrderInterface;
use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\Component\Payment\TransactionInterface;
use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;
use Adeliom\EasyShop\NotificationBundle\Backend\BackendInterface;
use Adeliom\EasyShop\NotificationBundle\Consumer\ConsumerEvent;
use Adeliom\EasyShop\NotificationBundle\Consumer\ConsumerInterface;

/**
 * Consumer for Order processing.
 */
class PaymentProcessOrderConsumer implements ConsumerInterface
{
    /**
     * @var OrderManagerInterface
     */
    protected $orderManager;

    /**
     * @var ManagerInterface
     */
    protected $transactionManager;

    /**
     * @var BackendInterface
     */
    protected $backend;

    public function __construct(OrderManagerInterface $orderManager, ManagerInterface $transactionManager, BackendInterface $backend)
    {
        $this->orderManager = $orderManager;
        $this->transactionManager = $transactionManager;
        $this->backend = $backend;
    }

    public function process(ConsumerEvent $event): void
    {
        $order = $this->getOrder($event);
        $transaction = $this->getTransaction($event);

        $orderElements = $order->getOrderElements();

        foreach ($orderElements as $orderElement) {
            $this->backend->createAndPublish('easy_shop_payment_order_element_process', [
                'product_id' => $orderElement->getProductId(),
                'transaction_status' => $transaction->getStatusCode(),
                'order_status' => $order->getStatus(),
                'quantity' => $orderElement->getQuantity(),
                'product_type' => $orderElement->getProductType(),
            ]);
        }
    }

    /**
     * Get the related Order.
     *
     * @throws \RuntimeException
     *
     * @return OrderInterface
     */
    protected function getOrder(ConsumerEvent $event)
    {
        $orderId = $event->getMessage()->getValue('order_id');

        $order = $this->orderManager->getOrder($orderId);

        if (!$order) {
            throw new \RuntimeException(sprintf('Unable to retrieve Order %d', $orderId));
        }

        return $order;
    }

    /**
     * Get the related Transaction.
     *
     * @throws \RuntimeException
     *
     * @return TransactionInterface
     */
    protected function getTransaction(ConsumerEvent $event)
    {
        $transactionId = $event->getMessage()->getValue('transaction_id');

        $transaction = $this->transactionManager->findOneBy([
            'id' => $transactionId,
        ]);

        if (!$transaction) {
            throw new \RuntimeException(sprintf('Unable to retrieve Transaction %d', $transactionId));
        }

        return $transaction;
    }
}
