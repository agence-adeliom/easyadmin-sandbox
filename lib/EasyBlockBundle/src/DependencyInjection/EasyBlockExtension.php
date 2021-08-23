<?php

namespace Adeliom\EasyBlockBundle\DependencyInjection;

use Adeliom\EasyBlockBundle\Block\BlockInterface;
use Adeliom\EasyBlockBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class EasyBlockExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $container->setParameter('easy_block.class', $config["block_class"]);
        $container->setParameter('easy_block.repository', $config["block_repository"]);


        $container->registerForAutoconfiguration(BlockInterface::class)
            ->addTag('easy_block.block')
        ;

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }


    public function getAlias()
    {
        return 'easy_block';
    }
}
