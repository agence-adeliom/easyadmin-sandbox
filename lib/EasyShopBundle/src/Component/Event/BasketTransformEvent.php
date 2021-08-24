<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Symfony\Contracts\EventDispatcher\Event;


class BasketTransformEvent extends Event
{
    /**
     * @var BasketInterface
     */
    protected $basket;

    public function __construct(BasketInterface $basket)
    {
        $this->basket = $basket;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    public function getBasket()
    {
        return $this->basket;
    }
}
