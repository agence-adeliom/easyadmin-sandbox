<?php

namespace Adeliom\EasyMenuBundle\DependencyInjection;

use Adeliom\EasyMenuBundle\Controller\BaseMenuCrudController;
use Adeliom\EasyMenuBundle\Controller\BaseMenuItemCrudController;
use Adeliom\EasyMenuBundle\Entity\BaseMenuEntity;
use Adeliom\EasyMenuBundle\Entity\BaseMenuItemEntity;
use Adeliom\EasyMenuBundle\Repository\BaseMenuItemRepository;
use Adeliom\EasyMenuBundle\Repository\BaseMenuRepository;
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
        $treeBuilder = new TreeBuilder('easy_menu');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('menu')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuEntity::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry class must be a valid class extending %s. "%s" given.',
                                            BaseMenuEntity::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('repository')
                            ->defaultValue(BaseMenuRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry repository must be a valid class extending %s. "%s" given.',
                                            BaseMenuRepository::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('crud')
                            ->defaultValue(BaseMenuCrudController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuCrudController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry crud controller must be a valid class extending %s. "%s" given.',
                                            BaseMenuCrudController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('menu_item')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuItemEntity::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category class must be a valid class extending %s. "%s" given.',
                                            BaseMenuItemEntity::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('repository')
                            ->defaultValue(BaseMenuItemRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuItemRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category repository must be a valid class extending %s. "%s" given.',
                                            BaseMenuItemRepository::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('crud')
                            ->defaultValue(BaseMenuItemCrudController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseMenuItemCrudController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category crud controller must be a valid class extending %s. "%s" given.',
                                            BaseMenuItemCrudController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->integerNode('ttl')->defaultValue(300)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
