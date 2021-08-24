<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\PaymentBundle\DependencyInjection;

use Adeliom\EasyShop\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class EasyShopPaymentExtension extends Extension
{
    /**
     * Loads the delivery configuration.
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
        $loader->load('consumer.xml');
        $loader->load('orm.xml');
        $loader->load('payment.xml');
        $loader->load('generator.xml');
        $loader->load('transformer.xml');
        $loader->load('selector.xml');
        $loader->load('browser.xml');
        $loader->load('form.xml');

        $this->registerDoctrineMapping($config);
        $this->registerParameters($container, $config);
        $this->configurePayment($container, $config);
        $this->configureSelector($container, $config['selector']);
        $this->configureTransformer($container, $config['transformers']);

        $container->setAlias('easy_shop.generator', $config['generator']);
    }

    /**
     * @param $config
     */
    public function registerParameters(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('easy_shop.payment.transaction.class', $config['class']['transaction']);
    }

    /**
     * @throws \RuntimeException
     */
    public function configurePayment(ContainerBuilder $container, array $config): void
    {
        // create the payment method pool
        $pool = $container->getDefinition('easy_shop.payment.pool');

        $internal = [
            'debug' => 'easy_shop.payment.method.debug',
            'pass' => 'easy_shop.payment.method.pass',
            'check' => 'easy_shop.payment.method.check',
            'scellius' => 'easy_shop.payment.method.scellius',
            'ogone' => 'easy_shop.payment.method.ogone',
            'paypal' => 'easy_shop.payment.method.paypal',
        ];

        $configured = [];

        // define the payment method
        foreach ($config['services'] as $id => $settings) {
            if (\array_key_exists($id, $internal)) {
                $id = $internal[$id];

                $name = $settings['name'] ?? 'n/a';
                $options = $settings['options'] ?? [];

                $code = $settings['code'] ?? false;

                if (!$code) {
                    throw new \RuntimeException('Please provide a code for the payment handler');
                }

                $definition = $container->getDefinition($id);

                $definition->addMethodCall('setName', [$name]);
                $definition->addMethodCall('setOptions', [$options]);
                $definition->addMethodCall('setCode', [$code]);

                foreach ((array) $settings['transformers'] as $name => $serviceId) {
                    $definition->addMethodCall('addTransformer', [$name, new Reference($serviceId)]);
                }

                $configured[$code] = $id;
            }
        }

        foreach ($config['methods'] as $code => $id) {
            if (\array_key_exists($code, $configured)) {
                // Internal service
                $id = $configured[$code];
            }

            if ($container->hasDefinition($id)) {
                $definition = $container->getDefinition($id);
                $definition->addMethodCall('setEnabled', [true]);
            }

            $pool->addMethodCall('addMethod', [new Reference($id)]);
        }

        if (isset($config['services']['debug'])) {
            $container->getDefinition('easy_shop.payment.method.debug')
                ->replaceArgument(1, new Reference($config['services']['debug']['browser']));
        }

        if (isset($config['services']['pass'])) {
            $container->getDefinition('easy_shop.payment.method.pass')
                ->replaceArgument(1, new Reference($config['services']['pass']['browser']));
        }

        if (isset($config['services']['check'])) {
            $container->getDefinition('easy_shop.payment.method.check')
                ->replaceArgument(2, new Reference($config['services']['check']['browser']));
        }

        if (isset($config['services']['scellius'])) {
            $container->getDefinition('easy_shop.payment.method.scellius')
                ->replaceArgument(3, new Reference($config['services']['scellius']['generator']));
        }

        // Remove unconfigured services
        foreach ($internal as $code => $id) {
            if (false === array_search($id, $configured, true)) {
                $container->removeDefinition($id);
            }
        }
    }

    /**
     * @param $selector
     */
    public function configureSelector(ContainerBuilder $container, $selector): void
    {
        $container->setAlias('easy_shop.payment.selector', $selector);
    }

    public function configureTransformer(ContainerBuilder $container, array $transformers): void
    {
        $pool = $container->getDefinition('easy_shop.payment.transformer.pool');

        foreach ($transformers as $type => $id) {
            $pool->addMethodCall('addTransformer', [$type, new Reference($id)]);
        }
    }

    public function registerDoctrineMapping(array $config): void
    {
        if (!class_exists($config['class']['transaction'])) {
            return;
        }

        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation($config['class']['transaction'], 'mapManyToOne', [
            'fieldName' => 'order',
            'targetEntity' => $config['class']['order'],
            'cascade' => [],
            'mappedBy' => null,
            'inversedBy' => null,
            'joinColumns' => [
                [
                    'name' => 'order_id',
                    'referencedColumnName' => 'id',
                    'onDelete' => 'SET NULL',
                ],
            ],
            'orphanRemoval' => false,
        ]);

        $collector->addIndex($config['class']['transaction'], 'status_code', [
            'status_code',
        ]);

        $collector->addIndex($config['class']['transaction'], 'state', [
            'state',
        ]);
    }
}
