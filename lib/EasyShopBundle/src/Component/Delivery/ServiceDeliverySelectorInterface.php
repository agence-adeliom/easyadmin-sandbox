<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Delivery;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Customer\AddressInterface;

interface ServiceDeliverySelectorInterface
{
    public function getAvailableMethods(?BasketInterface $basket = null, ?AddressInterface $deliveryAddress = null);
}
