<?php

declare(strict_types=1);

namespace App\Blocks;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceMaskBlockType extends AbstractBlock
{
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('type', ChoiceMaskType::class, [
            'label' => 'Content Type',
            'choices' => [
                'Text' => 'text',
                'Link' => 'link',
                'Both' => 'both',
            ],
            'map' => [
                'text' => ['content'],
                'link' => ['url', 'linkLabel'],
                'both' => ['content', 'url', 'linkLabel'],
            ],
        ]);

        $builder->add('content', TextType::class, [
            'label' => 'Content',
            'required' => false,
        ]);

        $builder->add('url', UrlType::class, [
            'label' => 'URL',
            'required' => false,
        ]);

        $builder->add('linkLabel', TextType::class, [
            'label' => 'Link Label',
            'required' => false,
        ]);
    }

    public function getName(): string
    {
        return 'Choice Mask Block';
    }

    public function getIcon(): string
    {
        return '';
    }

    public function getTemplate(): string
    {
        return 'blocks/choice_mask_block.html.twig';
    }

    /**
     * @return string[]
     */
    public static function configureAdminFormTheme(): array
    {
        return [
            '@EasyFields/form/choice_mask_widget.html.twig',
        ];
    }
}
