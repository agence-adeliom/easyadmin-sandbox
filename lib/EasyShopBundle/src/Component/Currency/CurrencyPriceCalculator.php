<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Adeliom\EasyShop\Component\Product\ProductInterface;


class CurrencyPriceCalculator implements CurrencyPriceCalculatorInterface
{
    public function getPrice(ProductInterface $product, CurrencyInterface $currency, $vat = false)
    {
        $price = $product->getPrice();

        if (!$vat && true === $product->isPriceIncludingVat()) {
            $price = bcdiv($price, bcadd('1', bcdiv($product->getVatRate(), '100')));
        }

        if ($vat && false === $product->isPriceIncludingVat()) {
            $price = bcmul($price, bcadd('1', bcdiv($product->getVatRate(), '100')));
        }

        return $price;
    }
}
