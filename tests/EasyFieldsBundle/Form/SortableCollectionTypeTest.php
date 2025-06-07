<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\SortableCollectionType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortableCollectionTypeTest extends TestCase
{
    public function testDefaultOptions(): void
    {
        $resolver = new OptionsResolver();
        (new SortableCollectionType())->configureOptions($resolver);
        $options = $resolver->resolve();
        $this->assertFalse($options['allow_drag']);
        $this->assertSame('__name__', $options['prototype_name']);
    }
}
