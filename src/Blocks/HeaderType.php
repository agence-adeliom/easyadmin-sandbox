<?php

namespace App\Blocks;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class HeaderType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("text", TextType::class);
    }

    public function getName(): string
    {
        return 'HeaderType';
    }

    public function getIcon(): string
    {
        return '';
    }

    public static function configureAssets(): array
    {
        return [
            "js" => [],
            "css" => [],
            "webpack" => ["app"],
        ];
    }

    public function getTemplate(): string
    {
        return "blocks/header.html.twig";
    }
}
