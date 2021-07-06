<?php


namespace Adeliom\EasyMediaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class EasyMediaExtension extends Extension implements PrependExtensionInterface
{

    public function load(array $configs, ContainerBuilder $container)
    {
        //$configuration = $this->getConfiguration($configs, $container);
        //$config = $this->processConfiguration($configuration, $configs);
        //var_dump($config);die;
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        foreach ($config as $k=>$v){
            $container->setParameter('adeliom_easymedia.'.$k, $v);
        }

        // TODO: Set custom parameters
        //$container->setParameter('acme_foo.bar', $config['bar']);
        //$container->setParameter('acme_foo.integer_foo', $config['integer_foo']);
        //$container->setParameter('acme_foo.integer_bar', $config['integer_bar']);
    }

    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        // TODO: Set custom doctrine config
        // $doctrineConfig = [];
        // $doctrineConfig['orm']['resolve_target_entities']['Acme\FooBundle\Entity\UserInterface'] = $config['user_provider'];
        // $doctrineConfig['orm']['mappings'][] = array(
        //     'name' => 'AcmeFooBundle',
        //     'is_bundle' => true,
        //     'type' => 'xml',
        //     'prefix' => 'Acme\FooBundle\Entity'
        // );
        // $container->prependExtensionConfig('doctrine', $doctrineConfig);
        // TODO: Set custom twig config
        $twigConfig = [];
        //$twigConfig['globals']['adeliom_easymedia_service'] = "@adeliom_easymedia.service";
        $twigConfig['paths'][__DIR__.'/../Resources/views'] = "adeliom_easymedia";
        $twigConfig['globals']['adeliom_easymedia'] = [];
        foreach ($config as $k=>$v){
            $twigConfig['globals']['adeliom_easymedia'][$k] = $v;
        }
        // $twigConfig['paths'][__DIR__.'/../Resources/public'] = "adeliom_easymedia.public";
        $container->prependExtensionConfig('twig', $twigConfig);
    }

    public function getAlias()
    {
        return 'adeliom_easymedia';
    }
}
