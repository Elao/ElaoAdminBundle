<?php

namespace Elao\Bundle\AdminBundle\Action\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
* Action Configuration
*/
abstract class ActionConfiguration implements ConfigurationInterface
{
    /**
     * Constructor
     */
    public function __construct($administration, $action, $serviceId)
    {
        $this->administration = $administration;
        $this->action         = $action;
        $this->serviceId      = $serviceId;
        $this->treeBuilder    = new TreeBuilder;
        $this->rootNode       = $this->treeBuilder->root('action');
    }

    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $this->buildConfiguration();

        return $this->treeBuilder;
    }

    /**
     * Build
     */
    protected function buildConfiguration()
    {
        $this->rootNode
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
            ->end()
        ;
    }
}
