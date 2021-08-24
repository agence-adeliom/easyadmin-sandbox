<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

/**
 * Interface PriceComputableInterface.
 *
 * This interface describes required fields for price computation
 *
 */
interface PriceComputableInterface
{
    /**
     * Returns the unit price.
     *
     * if $vat = true, returns the unit price with vat
     *
     * @param bool $vat
     *
     * @return float
     */
    public function getUnitPrice($vat = false);

    /**
     * Sets price.
     *
     * @param float $price
     */
    public function setPrice($price);

    /**
     * Returns price of the element (including quantity).
     *
     * @param bool $vat
     *
     * @return float
     */
    public function getPrice($vat = false);

    /**
     * Sets VAT rate.
     *
     * @param float $vatRate
     */
    public function setVatRate($vatRate);

    /**
     * Gets VAT rate.
     *
     * @return float
     */
    public function getVatRate();

    /**
     * Sets quantity.
     *
     * @param int $quantity
     */
    public function setQuantity($quantity);

    /**
     * Gets quantity.
     *
     * @return int $quantity
     */
    public function getQuantity();
}
