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
    private $factories;

    /**
     * Constructor.
     *
     * @param array $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elao_admin');

        $actionNodeBuilder = $rootNode
            ->children()
                ->arrayNode('administrations')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('model')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('repository')
                                ->defaultValue('elao_admin.model_manager.doctrine')
                            ->end()
                            /*->scalarNode('route_resolver')
                                ->defaultValue('elao_admin.route_resolver')
                            ->end()*/
                            ->arrayNode('actions')
                                ->isRequired()
                                ->disallowNewKeysInSubsequentConfigs()
                                ->cannotBeEmpty()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->addDefaultsIfNotSet()
                                    ->children();

        foreach ($this->factories as $factory) {
            $factoryNode = $actionNodeBuilder->arrayNode($factory->getKey())->canBeUnset();
            $factory->addConfiguration($factoryNode);
        }

        $actionNodeBuilder
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
