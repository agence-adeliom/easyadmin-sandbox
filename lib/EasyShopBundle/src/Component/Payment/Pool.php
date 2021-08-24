<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Payment;

/**
 * The pool stored a group of available payment method.
 */
class Pool
{
    /**
     * @var array
     */
    protected $methods = [];

    /**
     * add a payment method into the pool.
     *
     * @throws \RuntimeException
     */
    public function addMethod(PaymentInterface $instance): void
    {
        if (null === $instance->getCode()) {
            throw new \RuntimeException(sprintf('Payment handler of class %s must return a code on getCode method. Please refer to the documentation (https://sonata-project.org/bundles/ecommerce/master/doc/reference/bundles/payment/index.html)', \get_class($instance)));
        }

        $this->methods[$instance->getCode()] = $instance;
    }

    /**
     * @return array of payment methods
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * return a PaymentInterface Object.
     *
     * @param string $code
     *
     * @return PaymentInterface
     */
    public function getMethod($code)
    {
        return $this->methods[$code] ?? null;
    }
}
