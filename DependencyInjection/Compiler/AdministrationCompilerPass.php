<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\Administration;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\Action;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\ActionType;

/**
 * Adminsitration compile pass
 */
class AdministrationCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $defaultActions   = $container->getParameter('elao_admin.parameters.default_actions');
        $administrations  = $container->getParameter('elao_admin.parameters.administrations');
        $loaderDefinition = $container->getDefinition('elao_admin.routing_loader');
        $actionTypes      = $this->getActionTypes($container);

        foreach ($administrations as $name => $options) {

            if (!isset($options['actions']) || empty($options['actions'])) {
                $options['actions'] = array_combine($defaultActions, array_fill(0, count($defaultActions), []));
            }

            $administration = new Administration($name, $options, $actionTypes);

            $container->setDefinition(
                $administration->getModelManagerId(),
                $this->getModelManagerDefinition($administration)
            );

            $routeResolverDefinition = $this->getRouteResolverDefinition($administration);

            $container->setDefinition($administration->getRouteResolverId(), $routeResolverDefinition);

            $actions = $administration->getActions();

            foreach ($actions as $alias => $action) {
                $container->setDefinition($action->getServiceId(), $this->getActionDefinition($action));
                $routeResolverDefinition->addMethodCall('addAction', [$alias, $action->getRoute()]);
                $loaderDefinition->addMethodCall('addRoute', $action->getRoute());
            }
        }
    }

    /**
     * Register model manager service for given Administration
     *
     * @param Administration $administration
     *
     * @return DefinitionDecorator
     */
    protected function getModelManagerDefinition(Administration $administration)
    {
        $definition = new DefinitionDecorator($administration->getModelManager());

        $definition->replaceArgument(1, $administration->getModel());

        return $definition;
    }

    /**
     * Register route resolver service for given Administration
     *
     * @param Administration $administration
     *
     * @return DefinitionDecorator
     */
    protected function getRouteResolverDefinition(Administration $administration)
    {
        $definition = new DefinitionDecorator($administration->getRouteResolver());

        return $definition;
    }

    /**
     * Get action service definition
     *
     * @param Action $action
     *
     * @return DefinitionDecorator
     */
    protected function getActionDefinition(Action $action)
    {
        $administration = $action->getAdministration();
        $definition     = new DefinitionDecorator($action->getParentServiceId());

        $definition->addMethodCall('setModelManager', [new Reference($administration->getModelManagerId())]);
        $definition->addMethodCall('setRouteResolver', [new Reference($administration->getRouteResolverId())]);
        $definition->addMethodCall('setParameters', [$action->getParameters()]);

        return $definition;
    }

    /**
     * Load action types
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    protected function getActionTypes(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('elao_admin.action');
        $actions  = [];

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $alias = $attributes['alias'];

                if (preg_match('#^%.+%$#i', $attributes['configuration'])) {
                    if (!$container->hasParameter(trim($attributes['configuration'], '%'))) {
                        throw new InvalidConfigurationException(sprintf(
                            'Invalid configuration "%s" for service action "%s" : this parameter does not exist.',
                            $attributes['configuration'],
                            $alias
                        ));

                    }
                    $configuration = $container->getParameter(trim($attributes['configuration'], '%'));
                } else {
                    if (!class_exists($attributes['configuration'])) {
                        throw new InvalidConfigurationException(sprintf(
                            'Invalid configuration "%s" for service action "%s" : this class does not exist.',
                            $attributes['configuration'],
                            $alias
                        ));

                    }
                    $configuration = $attributes['configuration'];
                }

                $actions[$alias] = new ActionType($id, $alias, $configuration);
            }
        }

        return $actions;
    }
}
