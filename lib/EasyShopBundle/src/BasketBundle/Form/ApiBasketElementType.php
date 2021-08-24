<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\Form;

use Adeliom\EasyShop\Component\Form\Transformer\SerializeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ApiBasketElementType extends AbstractType
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $class An entity data class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            $builder->create('options')->addModelTransformer(new SerializeDataTransformer())
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'easy_shop_basket_api_form_basket_element';
    }

    public function getParent()
    {
        return ApiBasketElementParentType::class;
    }
}
