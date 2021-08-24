<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\BasketBundle\DependencyInjection;

use Adeliom\EasyShop\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * BasketExtension.
 *
 */
class EasyShopBasketExtension extends Extension
{
    /**
     * Loads the url shortener configuration.
     *
     * @param array            $configs   An array of configuration settings
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('orm.xml');
        $loader->load('basket_entity.xml');
        $loader->load('basket_session.xml');
        $loader->load('basket.xml');
        $loader->load('validator.xml');
        $loader->load('form.xml');
        $loader->load('twig.xml');

        if (isset($bundles['FOSRestBundle'], $bundles['NelmioApiDocBundle'])) {
            $loader->load('api_controllers.xml');
            $loader->load('api_form.xml');
        }

        $container->setAlias('easy_shop.basket.builder', $config['builder']);
        $container->setAlias('easy_shop.basket.factory', $config['factory']);
        $container->setAlias('easy_shop.basket.loader', $config['loader']);

        $this->registerParameters($container, $config);
        $this->registerDoctrineMapping($config);
    }

    /**
     * @param $config
     */
    public function registerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('easy_shop.basket.basket.class', $config['class']['basket']);
        $container->setParameter('easy_shop.basket.basket_element.class', $config['class']['basket_element']);
    }

    public function registerDoctrineMapping(array $config): void
    {
        if (!class_exists($config['class']['basket'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation($config['class']['basket'], 'mapManyToOne', [
             'fieldName' => 'customer',
             'targetEntity' => $config['class']['customer'],
             'cascade' => [],
             'mappedBy' => null,
             'inversedBy' => null,
             'joinColumns' => [
                 [
                     'name' => 'customer_id',
                     'referencedColumnName' => 'id',
                     'onDelete' => 'CASCADE',
                     'unique' => true,
                 ],
             ],
             'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['basket'], 'mapOneToMany', [
             'fieldName' => 'basketElements',
             'targetEntity' => $config['class']['basket_element'],
             'cascade' => [
                 'persist',
             ],
             'mappedBy' => 'basket',
             'orphanRemoval' => true,
        ]);

        $collector->addAssociation($config['class']['basket_element'], 'mapManyToOne', [
            'fieldName' => 'basket',
            'targetEntity' => $config['class']['basket'],
            'cascade' => [],
            'mappedBy' => null,
            'inversedBy' => 'basketElements',
            'joinColumns' => [
                [
                    'name' => 'basket_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ],
            ],
            'orphanRemoval' => false,
        ]);
    }
}
