<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Fixtures;

use Adeliom\EasyEditorBundle\Block\AbstractBlock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;

class DummyBlock extends AbstractBlock
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager);
    }

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        // no custom fields
    }

    public function getName(): string
    {
        return 'Dummy';
    }

    public function getIcon(): string
    {
        return '<i class="dummy"></i>';
    }

    public function getTemplate(): string
    {
        return 'dummy.html.twig';
    }
}
