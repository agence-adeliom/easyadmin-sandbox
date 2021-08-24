<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Transformer;

use Adeliom\EasyShop\Component\Delivery\Pool as DeliveryPool;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform a method code into a method instance.
 */
class DeliveryMethodTransformer implements DataTransformerInterface
{
    /**
     * @var DeliveryPool
     */
    protected $deliveryPool;

    public function __construct(DeliveryPool $deliveryPool)
    {
        $this->deliveryPool = $deliveryPool;
    }

    public function reverseTransform($value)
    {
        return $this->deliveryPool->getMethod($value);
    }

    public function transform($value)
    {
        return $value ? $value->getCode() : null;
    }
}
