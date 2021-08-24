<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Adeliom\EasyShop\Component\Product\ProductInterface;


interface CurrencyPriceCalculatorInterface
{
    /**
     * Returns the price of $product for given $currency.
     *
     * @param ProductInterface  $product  A product instance
     * @param CurrencyInterface $currency A currency instance
     * @param bool              $vat      Return price including VAT?
     *
     * @return float
     */
    public function getPrice(ProductInterface $product, CurrencyInterface $currency, $vat = false);
}
