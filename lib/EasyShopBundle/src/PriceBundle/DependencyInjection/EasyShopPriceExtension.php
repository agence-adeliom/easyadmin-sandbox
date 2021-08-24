<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PriceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class EasyShopPriceExtension extends Extension
{
    /**
     * Loads the price configuration.
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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('price.xml');

        $this->registerParameters($container, $config);
    }

    public function registerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('easy_shop.price.currency', $config['currency']);
        $container->setParameter('easy_shop.price.precision', $config['precision']);
    }
}
