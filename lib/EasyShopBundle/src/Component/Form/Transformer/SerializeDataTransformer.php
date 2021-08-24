<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;


class SerializeDataTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return serialize($value);
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return [];
        }

        return unserialize($value);
    }
}
