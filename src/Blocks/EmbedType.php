<?php

namespace App\Blocks;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Adeliom\EasyFieldsBundle\Form\OembedType;
use Symfony\Component\Form\FormBuilderInterface;

class EmbedType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add("embed", OembedType::class);
    }

    public function getName(): string
    {
        return 'EmbedType';
    }

    public function getIcon(): string
    {
        return '';
    }

    public function getTemplate(): string
    {
        return "blocks/embed.html.twig";
    }
}
