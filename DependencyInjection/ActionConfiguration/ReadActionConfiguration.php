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

use Symfony\Component\Config\Definition\Builder\NodeParentInterface;

/**
 * Handle specific configuration for the read action
 */
class ReadActionConfiguration extends ActionConfiguration
{
    /**
     * {@inheritdoc}
     */
    protected function configureParametersNode(NodeParentInterface $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('view')
                    ->cannotBeEmpty()
                    ->defaultValue($this->getView())
                ->end()
            ->end()
        ;

        return $node;
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
    protected function getRouteController()
    {
        return sprintf('%s:getResponse', $this->action->getServiceId());
    }

    /**
     * {@inheritdoc}
     */
    protected function getView()
    {
        return sprintf(
            ':%s:%s.html.twig',
            $this->action->getAdministration()->getName(),
            $this->action->getAlias()
        );
    }
}
