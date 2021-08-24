<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Twig\Extra\String\StringExtension;

final class TwigStringExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('twig.extension') as $id => $attributes) {
            if (StringExtension::class === $container->getDefinition($id)->getClass()) {
                return;
            }
        }

        $definition = new Definition(StringExtension::class);
        $definition->addTag('twig.extension');
        $container->setDefinition(StringExtension::class, $definition);
    }
}
