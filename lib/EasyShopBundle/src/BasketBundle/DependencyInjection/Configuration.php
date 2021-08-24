<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\DependencyInjection;

use Adeliom\EasyShop\BasketBundle\Form\BasketType;
use Adeliom\EasyShop\BasketBundle\Form\PaymentType;
use Adeliom\EasyShop\BasketBundle\Form\ShippingType;
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
        $treeBuilder = new TreeBuilder('easy_shop_basket');
        $node = $treeBuilder->getRootNode();

        $node
            ->children()
                ->scalarNode('builder')->defaultValue('easy_shop.basket.builder.standard')->cannotBeEmpty()->end()
                ->scalarNode('factory')->defaultValue('easy_shop.basket.session.factory')->cannotBeEmpty()->end()
                ->scalarNode('loader')->defaultValue('easy_shop.basket.loader.standard')->cannotBeEmpty()->end()
            ->end();

        $this->addModelSection($node);
        $this->addFormSection($node);

        return $treeBuilder;
    }

    private function addModelSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('basket')->defaultValue('App\\Adeliom\EasyShop\\BasketBundle\\Entity\\Basket')->end()
                        ->scalarNode('basket_element')->defaultValue('App\\Adeliom\EasyShop\\BasketBundle\\Entity\\BasketElement')->end()
                        ->scalarNode('customer')->defaultValue('App\\Adeliom\EasyShop\\CustomerBundle\\Entity\\Customer')->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addFormSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('basket')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue(BasketType::class)->end()
                                ->scalarNode('name')->defaultValue('easy_shop_basket_basket_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('shipping')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue(ShippingType::class)->end()
                                ->scalarNode('name')->defaultValue('easy_shop_shipping_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('payment')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue(PaymentType::class)->end()
                                ->scalarNode('name')->defaultValue('easy_shop_basket_payment_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
