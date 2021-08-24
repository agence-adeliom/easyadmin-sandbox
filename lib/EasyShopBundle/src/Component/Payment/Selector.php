<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;

use Psr\Log\LoggerInterface;
use Adeliom\EasyShop\Component\Basket\BasketInterface;
use Adeliom\EasyShop\Component\Customer\AddressInterface;
use Adeliom\EasyShop\Component\Payment\Pool as PaymentPool;
use Adeliom\EasyShop\Component\Product\Pool as ProductPool;

/**
 * The selector selects available payment methods depends on the provided basket.
 */
class Selector implements PaymentSelectorInterface
{
    /**
     * @var PaymentPool
     */
    protected $paymentPool;

    /**
     * @var ProductPool
     */
    protected $productPool;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(PaymentPool $paymentPool, ProductPool $productPool, ?LoggerInterface $logger = null)
    {
        $this->paymentPool = $paymentPool;
        $this->productPool = $productPool;
        $this->logger = $logger;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return \Adeliom\EasyShop\Component\Product\Pool
     */
    public function getProductPool()
    {
        return $this->productPool;
    }

    public function getAvailableMethods(?BasketInterface $basket = null, ?AddressInterface $billingAddress = null)
    {
        if (!$billingAddress) {
            return false;
        }

        return $this->getPaymentPool()->getMethods();
    }

    public function getPayment($bank)
    {
        if (!\array_key_exists($bank, $this->getPaymentPool()->getMethods())) {
            throw new PaymentNotFoundException($bank);
        }

        return $this->getPaymentPool()->getMethod($bank);
    }

    /**
     * @return PaymentPool|null
     */
    public function getPaymentPool()
    {
        return $this->paymentPool;
    }
}
