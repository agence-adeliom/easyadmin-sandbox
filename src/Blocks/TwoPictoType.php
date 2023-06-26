<?php

declare(strict_types=1);

namespace App\Blocks;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Adeliom\EasyFieldsBundle\Form\IconType;
use App\Fields\BlockFields;
use App\Form\Type\BlockColumnPictoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilderInterface;

class TwoPictoType extends AbstractBlock
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('icon1', IconType::class, [
            'required' => true,
            'json_url' => '/iconpicker/font-awesome-free.json',
        ]);

        $builder->add('icon2', IconType::class, [
            'required' => true,
            'json_url' => '/iconpicker/font-awesome-free.json',
        ]);
    }

    public function getName(): string
    {
        return 'Two picto';
    }

    public function getIcon(): string
    {
        return '';
    }

    public function getTemplate(): string
    {
        return '';
    }

    /**
     * @return string[]
     */
    public static function configureAdminFormTheme(): array
    {
        return [
            '@EasyFields/form/icon_widget.html.twig',
        ];
    }
}
