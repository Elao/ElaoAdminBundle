<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\Administration;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\ActionType;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ElaoAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('actions.xml');

        $loaderDefinition = $container->getDefinition('elao_admin.routing_loader');
        $actionTypes      = $this->getActionTypes($container);

        foreach ($config['administrations'] as $name => $options) {

            $administration    = (new Administration($name, $options))->processActions($actionTypes);
            $managerDefinition = new DefinitionDecorator($administration->getManager());

            $managerDefinition->addArgument($administration->getModel());

            $container->setDefinition($administration->getManagerId(), $managerDefinition);

            $actions = $administration->getActions();

            foreach ($actions as $alias => $action) {

                $actionDefinition = new DefinitionDecorator($action->getParentServiceId());

                $actionDefinition->addMethodCall('setModelManager', [new Reference($administration->getManagerId())]);
                $actionDefinition->addMethodCall('setParameters', [$action->getParameters()]);
                $loaderDefinition->addMethodCall('addRoute', $action->getRoute());

                $container->setDefinition($action->getServiceId(), $actionDefinition);
            }
        }
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
                $actions[$attributes['alias']] = new ActionType(
                    $id,
                    $attributes['alias'],
                    $container->getParameter(trim($attributes['configuration'], '%'))
                );
            }
        }

        return $actions;
    }
}
