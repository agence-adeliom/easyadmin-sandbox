<?php

namespace Adeliom\EasyBlogBundle\DependencyInjection;


use Adeliom\EasyBlogBundle\Controller\BaseCategoryController;
use Adeliom\EasyBlogBundle\Controller\BaseCategoryCrudController;
use Adeliom\EasyBlogBundle\Controller\BasePostController;
use Adeliom\EasyBlogBundle\Controller\BasePostCrudController;
use Adeliom\EasyBlogBundle\Entity\BaseCategoryEntity;
use Adeliom\EasyBlogBundle\Entity\BasePostEntity;
use Adeliom\EasyBlogBundle\Repository\BaseCategoryRepository;
use Adeliom\EasyBlogBundle\Repository\BasePostRepository;
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
        $treeBuilder = new TreeBuilder('easy_blog');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('post')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BasePostEntity::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Post class must be a valid class extending %s. "%s" given.',
                                            BasePostEntity::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('repository')
                            ->defaultValue(BasePostRepository::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BasePostRepository::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Post repository must be a valid class extending %s. "%s" given.',
                                            BasePostRepository::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('controller')
                            ->defaultValue(BasePostController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BasePostController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Page controller must be a valid class extending %s. "%s" given.',
                                            BasePostController::class, $value
                                        ));
                                    }
                                    return $value;
                                })
                            ->end()
                        ->end()
                        ->scalarNode('crud')
                            ->defaultValue(BasePostCrudController::class)
                            ->validate()
                                ->ifString()
                                ->then(function($value) {
                                    if (!class_exists($value) || !is_a($value, BasePostCrudController::class, true)) {
                                        throw new InvalidConfigurationException(sprintf(
                                            'Post crud controller must be a valid class extending %s. "%s" given.',
                                            BasePostCrudController::class, $value
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
                        ->scalarNode('root_path')->defaultValue('/blog')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
