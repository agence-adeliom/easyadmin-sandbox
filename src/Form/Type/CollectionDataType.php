<?php

declare(strict_types=1);

/*
 *  * This file has been edited by Adeliom.
 *  * Adeliom team <contact@adeliom.com>
 */

namespace App\Form\Type;

use Adeliom\EasyFieldsBundle\Form\SortableCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class CollectionDataType extends AbstractType implements FormTypeInterface
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
        ->add('button', TextType::class, [
            'label' => 'button',
            'required' => true,
        ])
        ->add('textList', SortableCollectionType::class, [
            'label' => 'textList',
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_drag' => true,
            'entry_options' => ['label' => false],
            'prototype_name' => '__textList__',
        ])
    ;
  }
}
