<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


class StatusRendererCompilerPass implements CompilerPassInterface
{
    /**
     * {@innheritdoc}.
     */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('easy_shop.status.renderer') as $id => $attributes) {
            $container->getDefinition('easy_shop.order.twig.status_extension')->addMethodCall('addStatusService', [new Reference($id)]);
        }
    }
}
