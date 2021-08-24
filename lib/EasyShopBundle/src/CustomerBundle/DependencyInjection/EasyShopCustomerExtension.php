<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\DependencyInjection;

use Adeliom\EasyShop\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class EasyShopCustomerExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
    }

    /**
     * Loads the customer configuration.
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
        $loader->load('block.xml');
        $loader->load('form.xml');
        $loader->load('menu.xml');
        $loader->load('orm.xml');
        $loader->load('twig.xml');

        if (isset($bundles['FOSRestBundle'], $bundles['NelmioApiDocBundle'])) {
            $loader->load('api_controllers.xml');
            $loader->load('api_form.xml');
            $loader->load('serializer.xml');
        }

        if (isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.xml');
        }

        $this->configureCustomerProfile($container, $config);
        $this->registerDoctrineMapping($config);
        $this->registerParameters($container, $config);
    }

    public function registerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('easy_shop.customer.customer.class', $config['class']['customer']);
        $container->setParameter('easy_shop.customer.address.class', $config['class']['address']);
        $container->setParameter('easy_shop.customer.selector.class', $config['class']['customer_selector']);

        $container->setParameter('easy_shop.customer.admin.customer.entity', $config['class']['customer']);
        $container->setParameter('easy_shop.customer.admin.address.entity', $config['class']['address']);
    }

    public function registerDoctrineMapping(array $config): void
    {
        if (!class_exists($config['class']['customer'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation($config['class']['customer'], 'mapOneToMany', [
            'fieldName' => 'addresses',
            'targetEntity' => $config['class']['address'],
            'cascade' => [
                'persist',
            ],
            'mappedBy' => 'customer',
            'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['customer'], 'mapOneToMany', [
            'fieldName' => 'orders',
            'targetEntity' => $config['class']['order'],
            'cascade' => [
                'persist',
            ],
            'mappedBy' => 'customer',
            'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['customer'], 'mapManyToOne', [
            'fieldName' => 'user',
            'targetEntity' => $config['class']['user'],
            'cascade' => [
                'persist',
            ],
            'mappedBy' => null,
            'inversedBy' => 'customers',
            'joinColumns' => [
                [
                    'name' => 'user_id',
                    'referencedColumnName' => $config['field']['customer']['user'],
                    'onDelete' => 'SET NULL',
                ],
            ],
            'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['user'], 'mapOneToMany', [
            'fieldName' => 'customers',
            'targetEntity' => $config['class']['customer'],
            'cascade' => [
                'persist',
            ],
            'mappedBy' => 'user',
            'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['address'], 'mapManyToOne', [
            'fieldName' => 'customer',
            'targetEntity' => $config['class']['customer'],
            'cascade' => [
                'persist',
            ],
            'mappedBy' => null,
            'inversedBy' => 'addresses',
            'joinColumns' => [
                [
                    'name' => 'customer_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'CASCADE',
                ],
            ],
            'orphanRemoval' => false,
        ]);
    }

    private function configureCustomerProfile(ContainerBuilder $container, array $config)
    {
        $container->setParameter('easy_shop.customer.profile.blocks', $config['profile']['blocks']);
        $container->setParameter('easy_shop.customer.profile.template', $config['profile']['template']);

        $container->setAlias('easy_shop.customer.profile.menu_builder', $config['profile']['menu_builder']);
        $container->getDefinition('easy_shop.customer.profile.menu_builder.default')->replaceArgument(2, $config['profile']['menu']);
    }
}
