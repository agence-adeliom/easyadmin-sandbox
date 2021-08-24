<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;

use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Customer\AddressInterface;

interface PaymentSelectorInterface
{
    /**
     * Returns the available Payment methods for given $basket and $deliveryAddress.
     *
     * @param BasketInterface  $basket
     * @param AddressInterface $deliveryAddress
     *
     * @return array
     */
    public function getAvailableMethods(?BasketInterface $basket = null, ?AddressInterface $deliveryAddress = null);

    /**
     * Returns the Payment method for given $bank.
     *
     * @param $bank The payment method code
     *
     * @throws PaymentNotFoundException
     *
     * @return PaymentInterface
     */
    public function getPayment($bank);
}
