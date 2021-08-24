<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Adeliom\EasyShop\Component\Product\ProductInterface;


class UnavailableForCurrencyException extends \Exception
{
    public function __construct(ProductInterface $product, CurrencyInterface $currency)
    {
        parent::__construct(sprintf("Product '%s' is not available for currency '%s'", $product->getName(), $currency->getLabel()));
    }
}
