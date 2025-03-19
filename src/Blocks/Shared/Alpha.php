<?php

namespace App\Blocks\Shared;

use Adeliom\EasyBlockBundle\Block\AbstractBlock;
use Adeliom\EasyBlockBundle\Block\BlockInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class Alpha extends AbstractBlock implements BlockInterface
{
    public function getName(): string
    {
        return 'Alpha';
    }

    public function getDescription(): string
    {
        return 'Create a alpha block';
    }

    public function getIcon(): string
    {
        return '<img src="https://i.stack.imgur.com/y9DpT.jpg" />';
    }

    public function getTemplate(): string
    {
        return 'blocks/alpha.html.twig';
    }

    public static function getDefaultSettings(): array
    {
        return [
          'name' => 'Default name',
        ];
    }

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
        ;
    }
}
