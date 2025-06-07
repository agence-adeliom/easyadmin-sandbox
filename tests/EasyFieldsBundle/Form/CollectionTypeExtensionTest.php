<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\Extension\CollectionTypeExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionTypeExtensionTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();
        (new CollectionTypeExtension())->configureOptions($resolver);
        $options = $resolver->resolve();
        $this->assertFalse($options['sortable']);
        $this->assertSame('form.collection.add', $options['button_add_label']);
    }
}
