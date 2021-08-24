<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form\Type;

use Adeliom\EasyShop\Component\Product\Pool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class VariationChoiceType extends AbstractType
{
    /**
     * @var Pool
     */
    protected $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = $this->pool->getProvider($options['product'])->getVariationsChoices($options['product'], $options['fields']);

        foreach ($choices as $choiceTitle => $choiceValues) {
            $choiceOptions = [
                'choices' => array_flip($choiceValues),
                'label' => sprintf('form_%s', $choiceTitle),
                'translation_domain' => 'SonataProductBundle',
            ];

            $builder->add($choiceTitle, ChoiceType::class, array_merge(
                $choiceOptions,
                $options['field_options']
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'field_options' => [],
            'product' => null,
            'fields' => null,
            'csrf_protection' => false,
            'method' => 'GET',
        ]);

        $resolver->setRequired(['product', 'fields']);
    }

    public function getBlockPrefix()
    {
        return 'sonata_product_variation_choices';
    }
}
