<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;


class Currency implements CurrencyInterface
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Currency
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function equals($currency)
    {
        if (!$currency instanceof CurrencyInterface) {
            return false;
        }

        return $this->getLabel() === $currency->getLabel();
    }

    /*
     * {@inheritdoc}
     */
//     public function getSymbol()
//     {
//         return $this->symbol;
//     }

    /*
     * @param string $symbol
     */
//     public function setSymbol($symbol)
//     {
//         $this->symbol = $symbol;
//         return $this;
//     }
}
