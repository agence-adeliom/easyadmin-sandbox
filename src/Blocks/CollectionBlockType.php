<?php

declare(strict_types=1);

/*
 *  * This file has been edited by Adeliom.
 *  * Adeliom team <contact@adeliom.com>
 */

namespace App\Blocks;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Adeliom\EasyFieldsBundle\Form\SortableCollectionType;
use App\Form\Type\CollectionDataType;
use App\Form\Type\DataType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CollectionBlockType extends AbstractBlock
{
  public function buildBlock(FormBuilderInterface $builder, array $options): void
  {
    $builder

        ->add('collection', SortableCollectionType::class, [
            'entry_type' => CollectionDataType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_drag' => true,
            'entry_options' => ['label' => false],
            'prototype_name' => '__collection__',
        ])
    ;
  }


  public function getName(): string
  {
    return 'CollectionBlockType';
  }

  public function getIcon(): array | string
  {
    return [''];
  }

  public function getTemplate(): string
  {
    return '';
  }

  public static function configureAdminAssets(): array
  {
    return [
        'js' => [
            'bundles/easyfields/form-type-collection-sortable.js',
        //    'bundles/easyfields/form-type-collection.js',

        ],
        'css' => [
            'bundles/easyfields/form-type-collection-sortable.css',
        ],
    ];
  }

  /**
   * @return string[]
   */
  public static function configureAdminFormTheme(): array
  {
    return ['@EasyFields/form/sortable_widget.html.twig'];
  }
}
