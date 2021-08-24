<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Adeliom\EasyShop\Doctrine\Model\ManagerInterface;


interface CurrencyManagerInterface extends ManagerInterface
{
    /**
     * Finds the currency matching $currencyLabel.
     *
     * @param string $currencyLabel
     *
     * @return CurrencyInterface|null
     */
    public function findOneByLabel($currencyLabel);
}
