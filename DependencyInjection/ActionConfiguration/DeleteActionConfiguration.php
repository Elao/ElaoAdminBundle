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
            ->scalarNode('view')
                ->cannotBeEmpty()
                ->defaultValue($this->getView())
            ->end()
            ->scalarNode('form_type')
                ->cannotBeEmpty()
                ->defaultValue('Elao\Bundle\AdminBundle\Form\Type\DeleteType')
            ->end()
            ->scalarNode('redirection')
                ->cannotBeEmpty()
                ->defaultValue($this->getRedirection())
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
        return sprintf('/%s/{id}/%s', $this->action->getAdministration()->getNameUrl(), $this->action->getAlias());
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
     * Get redirection
     *
     * @return string
     */
    protected function getRedirection()
    {
        return 'list';
    }
}
