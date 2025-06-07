<?php

declare(strict_types=1);

namespace App\Tests\EasyMediaBundle\Form;

use Adeliom\EasyMediaBundle\Form\EasyMediaType;
use Adeliom\EasyMediaBundle\Service\EasyMediaManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EasyMediaTypeTest extends TestCase
{
    public function testConfigureOptionsHasDefaults(): void
    {
        $manager = $this->createMock(EasyMediaManager::class);
        $type = new EasyMediaType($manager);
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve();
        self::assertArrayHasKey('editor', $options);
        self::assertTrue($options['editor']);
    }

    public function testParentAndPrefix(): void
    {
        $manager = $this->createMock(EasyMediaManager::class);
        $type = new EasyMediaType($manager);
        self::assertSame(TextType::class, $type->getParent());
        self::assertSame('easy_media', $type->getBlockPrefix());
    }
}
