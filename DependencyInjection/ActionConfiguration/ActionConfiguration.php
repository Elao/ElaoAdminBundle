<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\ActionConfiguration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;

/**
 * Handle routing configuration
 */
abstract class ActionConfiguration implements ConfigurationInterface
{
    /**
     * Constructor
     *
     * @param string $administration
     * @param string $action
     * @param string $serviceId
     */
    public function __construct($administration, $action, $serviceId)
    {
        $this->administration = $administration;
        $this->action         = $action;
        $this->serviceId      = $serviceId;
    }

    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        return $this->buildConfiguration();
    }

    /**
     * Build
     */
    protected function buildConfiguration()
    {
        $treeBuilder       = new TreeBuilder;
        $parametersBuilder = new TreeBuilder;
        $rootNode          = $treeBuilder->root('action');

        $rootNode
            ->children()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue(sprintf('%s_%s', $this->administration, $this->action))
                        ->end()
                        ->scalarNode('pattern')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue(sprintf('/%s', $this->administration))
                        ->end()
                        ->scalarNode('controller')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue(sprintf('%s:getResponse', $this->serviceId))
                        ->end()
                        ->arrayNode('parameters')
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('requirements')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
                ->append(
                    $this->configureParametersNode($parametersBuilder->root('parameters'))
                )
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Configure parameters node
     *
     * @param NodeParentInterface $node
     */
    protected function configureParametersNode(NodeParentInterface $node)
    {
        return $node;
    }
}
