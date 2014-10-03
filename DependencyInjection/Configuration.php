<?php

namespace Elao\Bundle\MicroAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elao_micro_admin');

        $rootNode
            ->children()
                ->arrayNode('actions')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->arrayNode('administrations')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('model')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('manager')
                                ->defaultValue('elao_micro_admin.model_manager.doctrine')
                            ->end()
                            ->arrayNode('actions')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->arrayNode('parameters')
                                            ->useAttributeAsKey('name')
                                            ->prototype('array')
                                                ->children()
                                                    ->scalarNode('value')->isRequired()->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
