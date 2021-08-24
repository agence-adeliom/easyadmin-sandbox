<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('easy_shop_customer');
        $node = $treeBuilder->getRootNode();

        $this->addModelSection($node);
        $this->addProfileSection($node);

        return $treeBuilder;
    }

    private function addModelSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('customer')->defaultValue('App\\Adeliom\EasyShop\\CustomerBundle\\Entity\\Customer')->end()
                        ->scalarNode('customer_selector')->defaultValue('Adeliom\EasyShop\\Component\\Customer\\CustomerSelector')->end()
                        ->scalarNode('address')->defaultValue('App\\Adeliom\EasyShop\\CustomerBundle\\Entity\\Address')->end()
                        ->scalarNode('order')->defaultValue('App\\Adeliom\EasyShop\\OrderBundle\\Entity\\Order')->end()
                        ->scalarNode('user')->defaultValue('App\\Adeliom\EasyShop\\UserBundle\\Entity\\User')->end()
                        ->scalarNode('user_identifier')->defaultValue('id')->end()
                    ->end()
                ->end()
                ->arrayNode('field')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('customer')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('user')->defaultValue('id')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addProfileSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('profile')
                    ->addDefaultsIfNotSet()
                    ->fixXmlConfig('block')
                    ->children()
                        ->scalarNode('template')
                            ->info('This is the profile template. You should extend your profile actions template by using {% extends easy_shop_customer.profileTemplate %}.')
                            ->cannotBeEmpty()
                            ->defaultValue('@SonataCustomer/Profile/action.html.twig')
                        ->end()
                        ->scalarNode('menu_builder')
                            ->info('MenuBuilder::createProfileMenu(array $itemOptions = []):ItemInterface is used to build profile menu.')
                            ->defaultValue('easy_shop.customer.profile.menu_builder.default')
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode('blocks')
                            ->info('Define your customer profile block here.')
                            ->defaultValue($this->getProfileBlocksDefaultValues())
                            ->prototype('array')
                                ->fixXmlConfig('setting')
                                ->children()
                                    ->scalarNode('type')->cannotBeEmpty()->end()
                                    ->arrayNode('settings')
                                        ->useAttributeAsKey('id')
                                        ->prototype('variable')->defaultValue([])->end()
                                    ->end()
                                    ->scalarNode('position')->defaultValue('right')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('menu')
                            ->info('Define your customer profile menu records here.')
                            ->prototype('array')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('route')->cannotBeEmpty()->end()
                                    ->arrayNode('route_parameters')
                                    ->defaultValue([])
                                    ->prototype('array')->end()
                                    ->end()
                                    ->scalarNode('label')->cannotBeEmpty()->end()
                                    ->scalarNode('domain')->defaultValue('messages')->end()
                                ->end()
                            ->end()
                            ->defaultValue($this->getProfileMenuDefaultValues())
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Returns default values for profile menu (to avoid BC Break).
     */
    private function getProfileMenuDefaultValues(): array
    {
        return [
            [
                'route' => 'easy_shop_customer_dashboard',
                'label' => 'link_list_dashboard',
                'domain' => 'SonataCustomerBundle',
                'route_parameters' => [],
            ],
            [
                'route' => 'easy_shop_customer_addresses',
                'label' => 'link_list_addresses',
                'domain' => 'SonataCustomerBundle',
                'route_parameters' => [],
            ],
            [
                'route' => 'easy_shop_order_index',
                'label' => 'order_list',
                'domain' => 'SonataOrderBundle',
                'route_parameters' => [],
            ],
        ];
    }

    private function getProfileBlocksDefaultValues(): array
    {
        return [
            [
                'position' => 'left',
                'type' => 'sonata.order.block.recent_orders',
                'settings' => ['title' => 'Recent Orders', 'number' => 5, 'mode' => 'public'],
            ],
            [
                'position' => 'right',
                'type' => 'sonata.news.block.recent_posts',
                'settings' => ['title' => 'Recent Posts', 'number' => 5, 'mode' => 'public'],
            ],
            [
                'position' => 'right',
                'type' => 'sonata.news.block.recent_comments',
                'settings' => ['title' => 'Recent Comments', 'number' => 5, 'mode' => 'public'],
            ],
        ];
    }
}
