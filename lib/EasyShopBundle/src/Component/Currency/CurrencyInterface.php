<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;


interface CurrencyInterface
{
    /**
     * Returns currency's label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Currency comparison.
     *
     * @param mixed $currency
     *
     * @return bool
     */
    public function equals($currency);

    /*
     * Returns currency's symbol
     *
     * @return string
     */
//     public function getSymbol();
}
