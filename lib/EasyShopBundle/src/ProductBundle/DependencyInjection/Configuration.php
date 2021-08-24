<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('easy_shop_product');
        $node = $treeBuilder->getRootNode();

        $this->addProductSection($node);
        $this->addModelSection($node);
        $this->addSeoSection($node);

        return $treeBuilder;
    }

    private function addProductSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('products')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('provider')->isRequired()->end()
                            ->scalarNode('manager')->isRequired()->end()
                            ->arrayNode('variations')
                                ->children()
                                    ->arrayNode('fields')
                                        ->isRequired()
                                        ->prototype('scalar')->end()
                                    ->end()
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addModelSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('product')->defaultValue('App\\Adeliom\EasyShop\\ProductBundle\\Entity\\Product')->end()
                        ->scalarNode('package')->defaultValue('App\\Adeliom\EasyShop\\ProductBundle\\Entity\\Package')->end()
                        ->scalarNode('product_category')->defaultValue('App\\Adeliom\EasyShop\\ProductBundle\\Entity\\ProductCategory')->end()
                        ->scalarNode('product_collection')->defaultValue('App\\Adeliom\EasyShop\\ProductBundle\\Entity\\ProductCollection')->end()
                        ->scalarNode('category')->defaultValue('App\\Adeliom\EasyShop\\ClassificationBundle\\Entity\\Category')->end()
                        ->scalarNode('collection')->defaultValue('App\\Adeliom\EasyShop\\ClassificationBundle\\Entity\\Collection')->end()
                        ->scalarNode('delivery')->defaultValue('App\\Adeliom\EasyShop\\ProductBundle\\Entity\\Delivery')->end()
                        ->scalarNode('media')->defaultValue('App\\Adeliom\EasyShop\\MediaBundle\\Entity\\Media')->end()
                        ->scalarNode('gallery')->defaultValue('App\\Adeliom\EasyShop\\MediaBundle\\Entity\\Gallery')->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addSeoSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('seo')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('product')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('site')->defaultValue('@sonataproject')->end()
                                ->scalarNode('creator')->defaultValue('@th0masr')->end()
                                ->scalarNode('domain')->defaultValue('http://demo.sonata-project.org')->end()
                                ->scalarNode('media_prefix')->defaultValue('http://demo.sonata-project.org')->end()
                                ->scalarNode('media_format')->defaultValue('reference')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
