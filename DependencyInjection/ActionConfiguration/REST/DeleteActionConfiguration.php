<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\ActionConfiguration\REST;

use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Elao\Bundle\AdminBundle\DependencyInjection\ActionConfiguration\ActionConfiguration;

/**
 * Handle specific configuration for the delete action
 */
class DeleteActionConfiguration extends ActionConfiguration
{
    /**
     * {@inheritdoc}
     */
    protected function buildParametersTree(NodeParentInterface $node)
    {
        return $node
            ->scalarNode('form_type')
                ->cannotBeEmpty()
                ->defaultValue('Elao\Bundle\AdminBundle\Form\Type\DeleteType')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteName()
    {
        return sprintf('%s_%s', $this->action->getAdministration()->getNameLowerCase(), $this->action->getAlias());
    }

    /**
     * {@inheritdoc}
     */
    protected function getRoutePattern()
    {
        return sprintf('/%s/{id}', $this->action->getAdministration()->getNameUrl());
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteRequirements()
    {
        return ['id' => '\d+'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteController()
    {
        return sprintf('%s:getResponse', $this->action->getServiceId());
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteMethods()
    {
        return ['DELETE'];
    }
}
