<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Customer;

interface CustomerSelectorInterface
{
    /**
     * Get the customer.
     *
     * @return \Adeliom\EasyShop\Component\Customer\CustomerInterface
     */
    public function get();
}
