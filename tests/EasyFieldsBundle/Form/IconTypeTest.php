<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\IconType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconTypeTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();
        (new IconType())->configureOptions($resolver);
        $options = $resolver->resolve();
        $this->assertSame('Search Icon', $options['search_placeholder']);
    }
}
