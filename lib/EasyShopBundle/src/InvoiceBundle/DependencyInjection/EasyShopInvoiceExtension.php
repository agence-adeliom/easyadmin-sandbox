<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\DependencyInjection;

use Adeliom\EasyShop\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class EasyShopInvoiceExtension extends Extension
{
    /**
     * Loads the order configuration.
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
        $loader->load('form.xml');
        $loader->load('renderer.xml');

        if (isset($bundles['FOSRestBundle'], $bundles['NelmioApiDocBundle'])) {
            $loader->load('api_controllers.xml');
            $loader->load('serializer.xml');
        }

        $this->registerParameters($container, $config);
        $this->registerDoctrineMapping($config);
    }

    public function registerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('easy_shop.invoice.invoice.class', $config['class']['invoice']);
        $container->setParameter('easy_shop.invoice.invoice_element.class', $config['class']['invoice_element']);

        $container->setParameter('easy_shop.invoice.admin.invoice.entity', $config['class']['invoice']);
        $container->setParameter('easy_shop.invoice.admin.invoice_element.entity', $config['class']['invoice_element']);
    }

    public function registerDoctrineMapping(array $config): void
    {
        if (!class_exists($config['class']['invoice'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();

        /*
         * INVOICE
         */
        $collector->addAssociation($config['class']['invoice'], 'mapManyToOne', [
             'fieldName' => 'customer',
             'targetEntity' => $config['class']['customer'],
             'cascade' => [
                 'persist',
                 'refresh',
                 'merge',
                 'detach',
             ],
             'mappedBy' => null,
             'joinColumns' => [
                 [
                     'name' => 'customer_id',
                     'referencedColumnName' => 'id',
                     'onDelete' => 'SET NULL',
                 ],
             ],
             'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['invoice_element'], 'mapManyToOne', [
             'fieldName' => 'invoice',
             'targetEntity' => $config['class']['invoice'],
             'cascade' => [
                 'persist',
                 'refresh',
                 'merge',
                 'detach',
             ],
             'mappedBy' => null,
             'inversedBy' => 'invoiceElements',
             'joinColumns' => [
                 [
                     'name' => 'invoice_id',
                     'referencedColumnName' => 'id',
                     'onDelete' => 'CASCADE',
                 ],
             ],
             'orphanRemoval' => false,
        ]);

        $collector->addAssociation($config['class']['invoice'], 'mapOneToMany', [
             'fieldName' => 'invoiceElements',
             'targetEntity' => $config['class']['invoice_element'],
             'cascade' => [
                 'persist',
             ],
             'mappedBy' => 'invoice',
             'orphanRemoval' => true,
        ]);

        $collector->addAssociation($config['class']['invoice_element'], 'mapManyToOne', [
            'fieldName' => 'orderElement',
            'targetEntity' => $config['class']['order_element'],
            'cascade' => [],
            'joinColumns' => [
                 [
                     'name' => 'order_element_id',
                     'referencedColumnName' => 'id',
                     'onDelete' => 'CASCADE',
                 ],
             ],
        ]);
    }
}
