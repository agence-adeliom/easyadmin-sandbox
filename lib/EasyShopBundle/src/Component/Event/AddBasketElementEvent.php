<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Basket\BasketElementInterface;
use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Adeliom\EasyShop\Component\Product\ProductProviderInterface;
use Symfony\Contracts\EventDispatcher\Event;


class AddBasketElementEvent extends Event
{
    /**
     * @var BasketInterface
     */
    protected $basket;

    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var BasketElementInterface
     */
    protected $basketElement;

    /**
     * @var ProductProviderInterface
     */
    protected $productProvider;

    public function __construct(BasketInterface $basket, BasketElementInterface $basketElement, ProductInterface $product, ProductProviderInterface $productProvider)
    {
        $this->basket = $basket;
        $this->basketElement = $basketElement;
        $this->product = $product;
        $this->productProvider = $productProvider;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Basket\BasketElementInterface
     */
    public function getBasketElement()
    {
        return $this->basketElement;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Product\ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Product\ProductProviderInterface
     */
    public function getProductProvider()
    {
        return $this->productProvider;
    }
}
