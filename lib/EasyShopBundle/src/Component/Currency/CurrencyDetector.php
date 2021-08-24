<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;


class CurrencyDetector implements CurrencyDetectorInterface
{
    /**
     * @var CurrencyInterface
     */
    protected $currency;

    /**
     * Constructs the currency detector service by finding the default currency.
     *
     * @param string $currencyLabel
     */
    public function __construct($currencyLabel, CurrencyManagerInterface $currencyManager)
    {
        $this->currency = $currencyManager->findOneByLabel($currencyLabel);
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}
