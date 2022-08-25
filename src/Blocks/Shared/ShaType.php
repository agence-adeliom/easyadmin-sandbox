<?php

namespace App\Blocks\Shared;

use Adeliom\EasyBlockBundle\Block\AbstractBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShaType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class);
    }

    public function getName(): string
    {
        return 'ShaType';
    }

    public function getIcon(): string
    {
        return '';
    }

    public function getTemplate(): string
    {
        return 'blocks/shared/sha.html.twig';
    }

    public function getDescription(): string
    {
        return '';
    }

    public static function getDefaultSettings(): array
    {
        return [
            'title' => 'Title',
        ];
    }

    public static function configureAssets(): array
    {
        return [
            'js' => [],
            'css' => [],
            'webpack' => ['app'],
        ];
    }
}
