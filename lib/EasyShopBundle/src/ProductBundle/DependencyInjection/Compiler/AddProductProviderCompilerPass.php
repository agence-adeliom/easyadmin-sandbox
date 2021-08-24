<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\DependencyInjection\Compiler;

use Adeliom\EasyShop\Component\Product\ProductDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


class AddProductProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $pool = $container->getDefinition('easy_shop.product.pool');

        $calls = $pool->getMethodCalls();
        $pool->setMethodCalls([]);

        $map = [];
        foreach ($calls as $method => $arguments) {
            if ('__hack' !== $arguments[0]) {
                $pool->addMethodCall($arguments[0], $arguments[1]);

                continue;
            }

            foreach ($arguments[1] as $code => $options) {
                // define a new ProductDefinition
                $definition = new Definition(ProductDefinition::class, [new Reference($options['provider']), new Reference($options['manager'])]);
                $definition->setPublic(false);
                $container->setDefinition($code, $definition);

                $container->getDefinition($options['provider'])->addMethodCall('setCode', [$code]);

                $pool->addMethodCall('addProduct', [$code, new Reference($code)]);

                $map[$code] = $container->getDefinition($options['manager'])->getArgument(0);

                $container->getDefinition($options['provider'])->addMethodCall('setBasketElementManager', [new Reference('easy_shop.basket_element.manager')]);
                $container->getDefinition($options['provider'])->addMethodCall('setCurrencyPriceCalculator', [new Reference('easy_shop.price.currency.calculator')]);
                $container->getDefinition($options['provider'])->addMethodCall('setProductCategoryManager', [new Reference('easy_shop.product_category.product')]);
                $container->getDefinition($options['provider'])->addMethodCall('setProductCollectionManager', [new Reference('easy_shop.product_collection.product')]);
                $container->getDefinition($options['provider'])->addMethodCall('setOrderElementClassName', [$container->getParameter('easy_shop.order.order_element.class')]);
                $container->getDefinition($options['provider'])->addMethodCall('setEventDispatcher', [new Reference('event_dispatcher')]);

                if (\array_key_exists('variations', $options)) {
                    $container->getDefinition($options['provider'])->addMethodCall('setVariationFields', [$options['variations']['fields']]);
                }
            }
        }

        $container->getDefinition('easy_shop.product.subscriber.orm')->replaceArgument(0, $map);
    }
}
