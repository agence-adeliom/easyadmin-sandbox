<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Form;

use Adeliom\EasyShop\Component\Currency\CurrencyFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiBasketType extends AbstractType
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var CurrencyFormType
     */
    protected $currencyFormType;

    /**
     * @param string           $class            An entity data class
     * @param CurrencyFormType $currencyFormType A EasyShop ecommerce currency form type
     */
    public function __construct($class, CurrencyFormType $currencyFormType)
    {
        $this->class = $class;
        $this->currencyFormType = $currencyFormType;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('currency', $this->currencyFormType)
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'csrf_protection' => false,
            'validation_groups' => ['api'],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'easy_shop_basket_api_form_basket';
    }

    public function getParent()
    {
        return ApiBasketParentType::class;
    }
}
