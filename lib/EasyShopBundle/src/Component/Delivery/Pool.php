<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Delivery;

/**
 * The pool stored a group of available delivery method.
 */
class Pool
{
    /**
     * @var array
     */
    protected $methods = [];

    /**
     * add a delivery method into the pool.
     */
    public function addMethod(ServiceDeliveryInterface $instance): void
    {
        $this->methods[$instance->getCode()] = $instance;
    }

    /**
     * @return array of delivery methods
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * return a ServiceDeliveryInterface Object.
     *
     * @param string $code
     *
     * @return ServiceDeliveryInterface
     */
    public function getMethod($code)
    {
        return $this->methods[$code] ?? null;
    }
}
