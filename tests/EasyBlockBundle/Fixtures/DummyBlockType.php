<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Fixtures;

use Adeliom\EasyBlockBundle\Block\AbstractBlock;
use Adeliom\EasyBlockBundle\Block\BlockInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DummyBlockType extends AbstractBlock implements BlockInterface
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager);
    }

    public function buildBlock(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
    }

    public function getName(): string
    {
        return 'Dummy';
    }

    public function getDescription(): string
    {
        return 'Dummy block';
    }

    public function getIcon(): string
    {
        return '<i>dummy</i>';
    }

    public function getTemplate(): string
    {
        return 'dummy.html.twig';
    }

    public static function configureAssets(): array
    {
        return [
            'js' => ['dummy.js'],
            'css' => [],
            'webpack' => [],
        ];
    }

    public static function getDefaultSettings(): array
    {
        return ['name' => 'Default'];
    }
}
