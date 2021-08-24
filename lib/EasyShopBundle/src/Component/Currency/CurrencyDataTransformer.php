<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Symfony\Component\Form\DataTransformerInterface;


class CurrencyDataTransformer implements DataTransformerInterface
{
    /**
     * @param CurrencyManagerInterface
     */
    private $currencyManager;

    /**
     * Constructs the CurrencyDataTransformer.
     */
    public function __construct(CurrencyManagerInterface $currencyManager)
    {
        $this->currencyManager = $currencyManager;
    }

    public function transform($value)
    {
        if ($value instanceof CurrencyInterface) {
            return $value->getLabel();
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        return $this->currencyManager->findOneByLabel($value);
    }
}
