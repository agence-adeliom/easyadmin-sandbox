<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('easy_shop_invoice');
        $node = $treeBuilder->getRootNode();

        $this->addModelSection($node);

        return $treeBuilder;
    }

    private function addModelSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('invoice')->defaultValue('App\\Adeliom\EasyShop\\InvoiceBundle\\Entity\\Invoice')->end()
                        ->scalarNode('invoice_element')->defaultValue('App\\Adeliom\EasyShop\\InvoiceBundle\\Entity\\InvoiceElement')->end()

                        ->scalarNode('order_element')->defaultValue('App\\Adeliom\EasyShop\\OrderBundle\\Entity\\OrderElement')->end()
                        ->scalarNode('customer')->defaultValue('App\\Adeliom\EasyShop\\CustomerBundle\\Entity\\Customer')->end()
                    ->end()
                ->end()
            ->end();
    }
}
