<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Cast the POST value to integer instead a string.
 *
 */
class QuantityTransformer implements DataTransformerInterface
{
    /**
     * @see \Symfony\Component\Form\DataTransformerInterface::transform()
     *
     * @param $quantity
     *
     * @return mixed
     */
    public function transform($quantity)
    {
        return $quantity;
    }

    /**
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     *
     * @param $quantity
     *
     * @return int
     */
    public function reverseTransform($quantity)
    {
        return (int) $quantity;
    }
}
