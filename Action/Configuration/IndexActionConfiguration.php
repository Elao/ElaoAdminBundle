<?php

namespace Elao\Bundle\AdminBundle\Action\Configuration;

/**
* Index Action Configuration
*/
 class IndexActionConfiguration extends ActionConfiguration
{
    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    protected function buildConfiguration()
    {
        parent::buildConfiguration();

        $this->rootNode
            ->children()
                ->scalarNode('view')
                    ->isRequired()
                    ->defaultValue('ElaoAdminBundle:Action:index.html.twig')
                ->end()
            ->end()
        ;
    }
}