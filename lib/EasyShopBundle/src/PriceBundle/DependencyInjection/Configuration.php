<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PriceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Intl\Intl;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('easy_shop_price');
        $node = $treeBuilder->getRootNode();

        $this->addPriceSection($node);
        $this->addPrecisionSection($node);

        return $treeBuilder;
    }

    /**
     * Sets the price precision section
     * Precision parameter will be given to bcscale() used in bundle boot() method.
     */
    private function addPrecisionSection(ArrayNodeDefinition $node): void
    {
        $node->children()->scalarNode('precision')->defaultValue(3)->end();
    }

    private function addPriceSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('currency')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray(array_keys(Intl::getCurrencyBundle()->getCurrencyNames()))
                        ->thenInvalid("Invalid currency '%s'")
                    ->end()
                ->end()
            ->end();
    }
}
