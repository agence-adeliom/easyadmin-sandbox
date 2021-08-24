<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Customer\AddressManagerInterface;
use Adeliom\EasyShop\Component\Delivery\Pool as DeliveryPool;
use Adeliom\EasyShop\Component\Payment\Pool as PaymentPool;
use Adeliom\EasyShop\Component\Product\Pool;

class BasketBuilder implements BasketBuilderInterface
{
    /**
     * @var \Adeliom\EasyShop\Component\Product\Pool
     */
    protected $productPool;

    /**
     * @var \Adeliom\EasyShop\Component\Customer\AddressManagerInterface
     */
    protected $addressManager;

    /**
     * @var \Adeliom\EasyShop\Component\Delivery\Pool
     */
    protected $deliveryPool;

    /**
     * @var \Adeliom\EasyShop\Component\Payment\Pool
     */
    protected $paymentPool;

    public function __construct(Pool $productPool, AddressManagerInterface $addressManager, DeliveryPool $deliveryPool, PaymentPool $paymentPool)
    {
        $this->productPool = $productPool;
        $this->addressManager = $addressManager;
        $this->deliveryPool = $deliveryPool;
        $this->paymentPool = $paymentPool;
    }

    /**
     * Build a basket.
     *
     * @param \Adeliom\EasyShop\Component\Basket\BasketInterface $basket
     *
     * @throws \RuntimeException
     */
    public function build(BasketInterface $basket): void
    {
        $basket->setProductPool($this->productPool);

        foreach ($basket->getBasketElements() as $basketElement) {
            if (null === $basketElement->getProduct()) {
                // restore information
                if (null === $basketElement->getProductCode()) {
                    throw new \RuntimeException('The product code is empty');
                }

                $productDefinition = $this->productPool->getProduct($basketElement->getProductCode());
                $basketElement->setProductDefinition($productDefinition);
            }
        }

        // load the delivery address
        $deliveryAddressId = $basket->getDeliveryAddressId();

        if ($deliveryAddressId) {
            $address = $this->addressManager->findOneBy(['id' => $deliveryAddressId]);

            $basket->setDeliveryAddress($address);
        }

        $deliveryMethodCode = $basket->getDeliveryMethodCode();

        if ($deliveryMethodCode) {
            $basket->setDeliveryMethod($this->deliveryPool->getMethod($deliveryMethodCode));
        }

        // load the payment address
        $billingAddressId = $basket->getBillingAddressId();

        if ($billingAddressId) {
            $address = $this->addressManager->findOneBy(['id' => $billingAddressId]);
            $basket->setBillingAddress($address);
        }

        // load the payment method
        $paymentMethodCode = $basket->getPaymentMethodCode();

        if ($paymentMethodCode) {
            $basket->setPaymentMethod($this->paymentPool->getMethod($paymentMethodCode));
        }

        $basket->buildPrices();
    }
}
