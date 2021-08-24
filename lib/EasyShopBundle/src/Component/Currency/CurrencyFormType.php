<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Currency;

use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;


class CurrencyFormType extends CurrencyType
{
    /**
     * @var CurrencyDataTransformer
     */
    private $currencyTransformer;

    public function __construct(CurrencyDataTransformer $currencyTransformer)
    {
        $this->currencyTransformer = $currencyTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer($this->currencyTransformer);
    }

    public function getParent()
    {
        return CurrencyType::class;
    }

    public function getBlockPrefix()
    {
        return 'sonata_currency';
    }
}
