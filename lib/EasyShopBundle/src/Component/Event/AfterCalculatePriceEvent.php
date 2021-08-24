<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Currency\CurrencyInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;


class AfterCalculatePriceEvent extends BeforeCalculatePriceEvent
{
    /**
     * @var float
     */
    protected $price;

    /**
     * @param bool  $vat
     * @param int   $quantity
     * @param float $price
     */
    public function __construct(ProductInterface $product, CurrencyInterface $currency, $vat, $quantity, $price)
    {
        parent::__construct($product, $currency, $vat, $quantity);
        $this->price = $price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}
