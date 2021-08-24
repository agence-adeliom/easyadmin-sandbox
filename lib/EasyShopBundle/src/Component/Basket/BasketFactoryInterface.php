<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Customer\CustomerInterface;

interface BasketFactoryInterface
{
    /**
     * Load the basket.
     *
     * @param \Adeliom\EasyShop\Component\Customer\CustomerInterface
     *
     * @return \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    public function load(CustomerInterface $customer);

    /**
     * Save the basket.
     *
     * @param \Adeliom\EasyShop\Component\Basket\BasketInterface
     */
    public function save(BasketInterface $basket);

    /**
     * Resets the basket.
     *
     * @param bool $full
     */
    public function reset(BasketInterface $basket, $full = true);
}
