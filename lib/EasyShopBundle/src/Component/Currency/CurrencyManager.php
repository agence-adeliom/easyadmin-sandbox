<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Adeliom\EasyShop\Doctrine\Entity\BaseEntityManager;


class CurrencyManager extends BaseEntityManager implements CurrencyManagerInterface
{
    public function findOneByLabel($currencyLabel)
    {
        $currency = new Currency();
        $currency->setLabel($currencyLabel);

        return $currency;
    }
}
