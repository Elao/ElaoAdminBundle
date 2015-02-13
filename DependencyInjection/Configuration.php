<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Admin Bundle Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elao_admin');

        $rootNode
            ->children()
                ->arrayNode('administrations')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('options')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->children()
                                    ->scalarNode('model')
                                        ->isRequired()
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('model_manager')
                                        ->defaultValue('elao_admin.model_manager.doctrine')
                                    ->end()
                                    ->scalarNode('route_resolver')
                                        ->defaultValue('elao_admin.route_resolver')
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('actions')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('type')
                                            ->defaultNull()
                                        ->end()
                                        ->variableNode('options')
                                            ->treatNullLike([])
                                            ->defaultValue([])
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
