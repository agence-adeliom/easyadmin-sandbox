<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\Form;

use Adeliom\EasyEditorBundle\Block\BlockCollection;
use Adeliom\EasyEditorBundle\Form\EditorCollectionType;
use App\Tests\EasyEditorBundle\Fixtures\DummyBlock;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EditorCollectionTypeTest extends TestCase
{
    public function testConfigureOptionsSetsDefaults(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $block = new DummyBlock($manager);
        $collection = new BlockCollection([$block]);
        $type = new EditorCollectionType($collection);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertArrayHasKey('blocks', $options);
        self::assertTrue($options['allow_extra_fields']);
        self::assertFalse($options['allow_drag']);
    }
}
