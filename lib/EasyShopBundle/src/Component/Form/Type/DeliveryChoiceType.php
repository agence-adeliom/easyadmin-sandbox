<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Type;

use Adeliom\EasyShop\Component\Delivery\Pool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryChoiceType extends AbstractType
{
    protected $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix()
    {
        return 'sonata_delivery_choice';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = [];

        foreach ($this->pool->getMethods() as $name => $instance) {
            $choices[$name] = $instance->getName();
        }

        $resolver->setDefaults([
            'choices' => $choices,
        ]);
    }
}
