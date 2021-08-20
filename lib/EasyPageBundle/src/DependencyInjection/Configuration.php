<?php

namespace Adeliom\EasyPageBundle\DependencyInjection;


use Adeliom\EasyPageBundle\Controller\BasePageController;
use Adeliom\EasyPageBundle\Entity\BasePageEntity;
use Adeliom\EasyPageBundle\Repository\BasePageRepository;
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
        $treeBuilder = new TreeBuilder('easy_page');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('page_class')
                    ->isRequired()
                    ->validate()
                        ->ifString()
                        ->then(function($value) {
                            if (!class_exists($value) || !is_a($value, BasePageEntity::class, true)) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Page class must be a valid class extending %s. "%s" given.',
                                    BasePageEntity::class, $value
                                ));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('page_repository')
                    ->defaultValue(BasePageRepository::class)
                    ->validate()
                        ->ifString()
                        ->then(function($value) {
                            if (!class_exists($value) || !is_a($value, BasePageRepository::class, true)) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Page repository must be a valid class extending %s. "%s" given.',
                                    BasePageRepository::class, $value
                                ));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
                ->scalarNode('page_controller')
                    ->defaultValue(BasePageController::class)
                    ->validate()
                        ->ifString()
                        ->then(function($value) {
                            if (!class_exists($value) || !is_a($value, BasePageController::class, true)) {
                                throw new InvalidConfigurationException(sprintf(
                                    'Page controller must be a valid class extending %s. "%s" given.',
                                    BasePageController::class, $value
                                ));
                            }
                            return $value;
                        })
                    ->end()
                ->end()
                ->arrayNode('layouts')
                    ->defaultValue([
                        'front' => [
                            'resource' => '@EasyPage/default_layout.html.twig',
                            'pattern' => '',
                        ],
                    ])
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('resource')->isRequired()->end()
                            ->arrayNode('assets_css')->prototype('scalar')->end()->end()
                            ->arrayNode('assets_js')->prototype('scalar')->end()->end()
                            ->arrayNode('assets_webpack')->prototype('scalar')->end()->end()
                            ->scalarNode('pattern')->defaultValue('')->end()
                            ->scalarNode('host')->defaultValue('')->end()
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
