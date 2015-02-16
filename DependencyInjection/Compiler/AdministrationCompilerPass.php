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
     * Container
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * Action types
     *
     * @var ContainerBuilder
     */
    private $actionTypes;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;

        $administrations       = $container->getParameter('elao_admin.parameters.administrations');
        $routeLoaderDefinition = $container->getDefinition('elao_admin.routing_loader');
        $securityDefinition    = $container->getDefinition('elao_admin.event.subscriber.security');

        foreach ($administrations as $name => $config) {
            $administration          = new Administration($name, $config['options']);
            $routeResolverDefinition = $this->getRouteResolverDefinition($administration);
            $modelManagerDefinition  = $this->getModelManagerDefinition($administration);
            $container->setDefinition($administration->getModelManagerId(), $modelManagerDefinition);
            $container->setDefinition($administration->getRouteResolverId(), $routeResolverDefinition);

            foreach ($config['actions'] as $alias => $actionConfig) {
                $type   = $this->getActionType($actionConfig['type'] ?: $alias);
                $action = new Action($alias, $type, $administration, $actionConfig['options']);

                $administration->addAction($action);
                $container->setDefinition($action->getServiceId(), $this->getActionDefinition($action));
                $routeResolverDefinition->addMethodCall('addAction', [$action->getAlias(), $action->getRoute()]);
                $routeLoaderDefinition->addMethodCall('addRoute', $action->getRoute());

                if ($action->isSecure()) {
                    $securityDefinition->addMethodCall('setRouteSecurity', [$action->getRoute()['name'], $action->getSecurity()]);
                }
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
    private function getModelManagerDefinition(Administration $administration)
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
    private function getRouteResolverDefinition(Administration $administration)
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
    private function getActionDefinition(Action $action)
    {
        $administration = $action->getAdministration();
        $definition     = new DefinitionDecorator($action->getParentServiceId());

        $definition->addMethodCall('setModelManager', [new Reference($administration->getModelManagerId())]);
        $definition->addMethodCall('setRouteResolver', [new Reference($administration->getRouteResolverId())]);
        $definition->addMethodCall('setParameters', [$action->getParameters()]);

        return $definition;
    }

    /**
     * Get action type
     *
     * @param string $type
     *
     * @return ActionType
     */
    private function getActionType($type)
    {
        $actionTypes = $this->getActionTypes();

        if (!array_key_exists($type, $actionTypes)) {
            throw new \Exception(sprintf(
                'Unkown action "%s", availables actions are: %s',
                $type,
                join(', ', array_keys($actionTypes))
            ));
        }

        return $actionTypes[$type];
    }

    /**
     * Load action types
     *
     * @return array
     */
    private function getActionTypes()
    {
        if (!isset($this->actionTypes)) {
            $this->actionTypes = [];
            $services = $this->container->findTaggedServiceIds('elao_admin.action');

            foreach ($services as $id => $tags) {
                foreach ($tags as $attributes) {
                    $alias = $attributes['alias'];

                    if (preg_match('#^%.+%$#i', $attributes['configuration'])) {
                        if (!$this->container->hasParameter(trim($attributes['configuration'], '%'))) {
                            throw new InvalidConfigurationException(sprintf(
                                'Invalid configuration "%s" for service action "%s" : this parameter does not exist.',
                                $attributes['configuration'],
                                $alias
                            ));
                        }
                        $configuration = $this->container->getParameter(trim($attributes['configuration'], '%'));
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

                    $this->actionTypes[$alias] = new ActionType($id, $alias, $configuration);
                }
            }
        }

        return $this->actionTypes;
    }
}
