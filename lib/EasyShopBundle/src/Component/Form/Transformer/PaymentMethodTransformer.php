<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Transformer;

use Adeliom\EasyShop\Component\Payment\Pool as PaymentPool;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform a method code into a method instance.
 */
class PaymentMethodTransformer implements DataTransformerInterface
{
    /**
     * @var PaymentPool
     */
    protected $paymentPool;

    public function __construct(PaymentPool $paymentPool)
    {
        $this->paymentPool = $paymentPool;
    }

    public function reverseTransform($value)
    {
        return $this->paymentPool->getMethod($value);
    }

    public function transform($value)
    {
        return $value ? $value->getCode() : null;
    }
}
