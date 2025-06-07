<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\Extension\FormTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTypeExtensionTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();
        (new FormTypeExtension())->configureOptions($resolver);
        $options = $resolver->resolve();
        $this->assertSame('', $options['column_size']);
    }
}
