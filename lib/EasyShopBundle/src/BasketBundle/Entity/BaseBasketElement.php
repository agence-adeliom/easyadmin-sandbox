<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Entity;

use Adeliom\EasyShop\Component\Basket\BasketElement;
use Adeliom\EasyShop\Component\Basket\BasketInterface;

abstract class BaseBasketElement extends BasketElement
{
    /**
     * @var \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    protected $basket;

    /**
     * Get basket.
     *
     * @return \Adeliom\EasyShop\Component\Basket\BasketInterface $basket
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * Set basket.
     */
    public function setBasket(BasketInterface $basket): void
    {
        $this->basket = $basket;
    }
}
