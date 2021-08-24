<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Order\OrderInterface;
use Adeliom\EasyShop\Component\Payment\TransactionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;


class PaymentEvent extends Event
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var TransactionInterface
     */
    protected $transaction;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param TransactionInterface $transaction
     * @param Response             $response
     */
    public function __construct(OrderInterface $order, ?TransactionInterface $transaction = null, ?Response $response = null)
    {
        $this->order = $order;
        $this->transaction = $transaction;
        $this->response = $response;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Order\OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Payment\TransactionInterface
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
