<?php

namespace Adeliom\EasyBlockBundle\DependencyInjection;

use Adeliom\EasyBlockBundle\Entity\BaseBlockEntity;
use Adeliom\EasyBlockBundle\Repository\BaseBlockRepository;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;


/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('easy_block');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('block_class')
                    ->isRequired()
                    ->validate()
                        ->ifString()
                        ->then(function($value) {
                            if (!class_exists($value) || !is_a($value, BaseBlockEntity::class, true)) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Block class must be a valid class extending %s. "%s" given.',
                                    BaseBlockEntity::class, $value
                                ));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('block_repository')
                    ->defaultValue(BaseBlockRepository::class)
                    ->validate()
                        ->ifString()
                        ->then(function($value) {
                            if (!class_exists($value) || !is_a($value, BaseBlockRepository::class, true)) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Block repository must be a valid class extending %s. "%s" given.',
                                    BaseBlockRepository::class, $value
                                ));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
