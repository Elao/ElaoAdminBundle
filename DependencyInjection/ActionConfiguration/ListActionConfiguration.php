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
 * Handle specific configuration for the list action
 */
class ListActionConfiguration extends ActionConfiguration
{
    /**
     * {@inheritdoc}
     */
    protected function buildParametersTree(NodeParentInterface $node)
    {
        return $node
            ->scalarNode('view')
                ->defaultValue($this->getView())
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('per_page')
                ->defaultValue(10)
                ->cannotBeEmpty()
            ->end()
            ->arrayNode('filters')
                ->canBeDisabled()
                ->children()
                    ->scalarNode('form_type')
                        ->cannotBeEmpty()
                        ->defaultValue($this->getFilterFormType())
                    ->end()
                    ->scalarNode('data')
                        ->defaultNull()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteName()
    {
        return sprintf('%s', $this->action->getAdministration()->getNameLowerCase());
    }

    /**
     * {@inheritdoc}
     */
    protected function getRoutePattern()
    {
        return sprintf('/%s', $this->action->getAdministration()->getNameUrl());
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

    /**
     * Get form type classname or service id for filter form
     *
     * @return string
     */
    protected function getFilterFormType()
    {
        return sprintf('%s_filter', $this->action->getAdministration()->getNameLowerCase());
    }
}
