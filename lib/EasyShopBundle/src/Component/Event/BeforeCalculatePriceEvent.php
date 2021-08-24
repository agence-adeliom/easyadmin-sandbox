<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;

use Adeliom\EasyShop\Component\Currency\CurrencyInterface;
use Adeliom\EasyShop\Component\Product\ProductInterface;
use Symfony\Contracts\EventDispatcher\Event;


class BeforeCalculatePriceEvent extends Event
{
    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * @var bool
     */
    protected $vat;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @param bool $vat
     * @param int  $quantity
     */
    public function __construct(ProductInterface $product, CurrencyInterface $currency, $vat, $quantity)
    {
        $this->product = $product;
        $this->currency = $currency;
        $this->vat = $vat;
        $this->quantity = $quantity;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Currency\CurrencyInterface
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Product\ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param bool $vat
     */
    public function setVat($vat): void
    {
        $this->vat = $vat;
    }

    /**
     * @return bool
     */
    public function getVat()
    {
        return $this->vat;
    }
}
