<?php

namespace Adeliom\EasyMediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EasyMediaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        /*$resolver->setDefaults([
            'choices' => [
                'Standard Shipping' => 'standard',
                'Expedited Shipping' => 'expedited',
                'Priority Shipping' => 'priority',
            ],
        ]);*/
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
