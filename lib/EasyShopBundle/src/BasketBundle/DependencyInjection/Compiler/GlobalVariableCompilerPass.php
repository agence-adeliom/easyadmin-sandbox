<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GlobalVariableCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @api
     */
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', ['easy_shop_basket', new Reference('easy_shop.basket.twig.global')]);
    }
}
