<?php

namespace Adeliom\EasyMediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('adeliom_easymedia');

        $rootNode = $builder->getRootNode();
        $rootNode->children()
            ->scalarNode('storage')
                ->defaultValue('%kernel.project_dir%/public/upload')
            ->end()
            ->scalarNode('base_url')
                ->defaultValue('/upload/')
            ->end()
            ->scalarNode('lock_entity')
                ->defaultValue('App\Entity\EasyMediaLock')
            ->end()
            ->scalarNode('metas_entity')
                ->defaultValue('App\Entity\EasyMediaMetas')
            ->end()
            ->scalarNode('ignore_files')
                ->defaultValue('/^\..*/')
            ->end()
            ->scalarNode('allowed_fileNames_chars')
                ->defaultValue("\._\-\'\s\(\),")
            ->end()
            ->scalarNode('allowed_folderNames_chars')
                ->defaultValue("_\-\s")
            ->end()
            ->arrayNode('unallowed_mimes')
                ->scalarPrototype()->end()
                ->defaultValue([
                    'php',
                    'java',
                ])
            ->end()
            ->arrayNode('unallowed_ext')
                ->defaultValue([
                    'php',
                    'jav',
                    'py',
                ])
                ->scalarPrototype()->end()
            ->end()
            ->arrayNode('extended_mimes')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('image')->scalarPrototype()->end()->isRequired()->defaultValue(['binary/octet-stream'])->end()
                    ->arrayNode('archive')->scalarPrototype()->end()->isRequired()->defaultValue(['application/x-tar', 'application/zip'])->end()
                ->end()
            ->end()
            ->scalarNode('sanitized_text')
                ->defaultValue('uniqid')
            ->end()
            ->scalarNode('last_modified_format')
                ->defaultValue('Y-m-d')
            ->end()
            ->booleanNode('hide_files_ext')
                ->defaultTrue()
            ->end()
            ->booleanNode('get_folder_info')
                ->defaultTrue()
            ->end()
            ->booleanNode('enable_broadcasting')
                ->defaultFalse()
            ->end()
            ->booleanNode('show_ratio_bar')
                ->defaultTrue()
            ->end()
            ->booleanNode('preview_files_before_upload')
                ->defaultTrue()
            ->end()
            ->integerNode('pagination_amount')
                ->defaultValue(50)
                ->min(4)
            ->end()
        ->end();

        return $builder;
    }
}
