<?php

namespace App\Blocks\Shared;

use Adeliom\EasyBlockBundle\Block\AbstractBlock;
use Adeliom\EasyBlockBundle\Block\BlockInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class Test extends AbstractBlock implements BlockInterface
{
    public function getName(): string
    {
        return 'test';
    }

    public function getDescription(): string
    {
        return 'Lotem';
    }

    public function getIcon(): string
    {
        return "<span class='fa fa-yen'></span>";
    }

    public function getTemplate(): string
    {
        return 'blocks/test.html.twig';
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
