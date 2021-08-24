<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Order\OrderInterface;
use Symfony\Contracts\EventDispatcher\Event;


class OrderTransformEvent extends Event
{
    /**
     * @var OrderInterface
     */
    protected $order;

    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Order\OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }
}
