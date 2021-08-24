<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class GlobalVariablesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', ['easy_shop_customer', new Reference('easy_shop.customer.twig.global')]);
    }
}
