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

/**
 * Handle specific configuration for the index action
 */
class IndexActionConfiguration extends ActionConfiguration
{
    /**
     * {@inheritdoc}
     */
    protected function buildConfiguration()
    {
        parent::buildConfiguration();

        $this->rootNode
            ->children()
                ->scalarNode('view')
                    ->cannotBeEmpty()
                    ->defaultValue('ElaoAdminBundle:Action:index.html.twig')
                ->end()
            ->end()
        ;
    }
}
