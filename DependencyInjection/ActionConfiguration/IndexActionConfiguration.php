<?php

/*
 * This file is part of the "project".
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\ActionConfiguration;

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
