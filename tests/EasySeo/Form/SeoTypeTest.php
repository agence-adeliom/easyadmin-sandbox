<?php
declare(strict_types=1);

namespace App\Tests\EasySeo\Form;

use Adeliom\EasySeoBundle\Entity\SEO;
use Adeliom\EasySeoBundle\Form\SeoType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SeoTypeTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $type = new SeoType();
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve();

        self::assertSame(SEO::class, $options['data_class']);
        self::assertFalse($options['label']);
        self::assertSame('easy_seo', $type->getBlockPrefix());
    }
}
