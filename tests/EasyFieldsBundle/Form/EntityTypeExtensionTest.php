<?php

declare(strict_types=1);

namespace App\Tests\EasyFieldsBundle\Form;

use Adeliom\EasyFieldsBundle\Form\Extension\EntityTypeExtension;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Provider\AdminContextProviderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Router\AdminRouteGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Registry\DashboardControllerRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTypeExtensionTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();
        $adminUrlGenerator = new AdminUrlGenerator(
            $this->createMock(AdminContextProviderInterface::class),
            $this->createMock(UrlGeneratorInterface::class),
            $this->createMock(DashboardControllerRegistryInterface::class),
            $this->createMock(AdminRouteGeneratorInterface::class)
        );
        $extension = new EntityTypeExtension($adminUrlGenerator);
        $extension->configureOptions($resolver);
        $options = $resolver->resolve();
        $this->assertFalse($options['allow_add']);
        $this->assertSame('action.add_new_item', $options['button_add_label']);
    }
}
