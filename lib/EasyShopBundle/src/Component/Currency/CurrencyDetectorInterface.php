<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;


interface CurrencyDetectorInterface
{
    /**
     * @return CurrencyInterface
     */
    public function getCurrency();
}
