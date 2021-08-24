<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Customer\CustomerInterface;

class BasketSessionFactory extends BaseBasketFactory
{
    public function load(CustomerInterface $customer)
    {
        // always clone the basket so it can be only saved by calling
        // the save method
        return clone parent::load($customer);
    }

    public function save(BasketInterface $basket): void
    {
        $this->storeInSession($basket);
    }

    public function reset(BasketInterface $basket, $full = true): void
    {
        if ($full) {
            $this->clearSession($basket->getCustomer());
        } else {
            $basket->reset($full);
            $this->save($basket);
        }
    }
}
