<?php

namespace Adeliom\EasyFaqBundle\DependencyInjection;


use Adeliom\EasyFaqBundle\Controller\BaseCategoryController;
use Adeliom\EasyFaqBundle\Controller\BaseCategoryCrudController;
use Adeliom\EasyFaqBundle\Controller\BaseEntryController;
use Adeliom\EasyFaqBundle\Controller\BaseEntryCrudController;
use Adeliom\EasyFaqBundle\Entity\BaseCategoryEntity;
use Adeliom\EasyFaqBundle\Entity\BaseEntryEntity;
use Adeliom\EasyFaqBundle\Repository\BaseCategoryRepository;
use Adeliom\EasyFaqBundle\Repository\BaseEntryRepository;
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
        $treeBuilder = new TreeBuilder('easy_faq');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('entry')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseEntryEntity::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry class must be a valid class extending %s. "%s" given.',
                                            BaseEntryEntity::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('repository')
                            ->defaultValue(BaseEntryRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseEntryRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry repository must be a valid class extending %s. "%s" given.',
                                            BaseEntryRepository::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('controller')
                            ->defaultValue(BaseEntryController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseEntryController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Page controller must be a valid class extending %s. "%s" given.',
                                            BaseEntryController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('crud')
                            ->defaultValue(BaseEntryCrudController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseEntryCrudController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Entry crud controller must be a valid class extending %s. "%s" given.',
                                            BaseEntryCrudController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('category')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseCategoryEntity::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category class must be a valid class extending %s. "%s" given.',
                                            BaseCategoryEntity::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('repository')
                            ->defaultValue(BaseCategoryRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseCategoryRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category repository must be a valid class extending %s. "%s" given.',
                                            BaseCategoryRepository::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('controller')
                            ->defaultValue(BaseCategoryController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseCategoryController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category controller must be a valid class extending %s. "%s" given.',
                                            BaseCategoryController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('crud')
                            ->defaultValue(BaseCategoryCrudController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BaseCategoryCrudController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Category crud controller must be a valid class extending %s. "%s" given.',
                                            BaseCategoryCrudController::class, $value
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
                ->arrayNode('page')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('root_path')->defaultValue('/faq')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
