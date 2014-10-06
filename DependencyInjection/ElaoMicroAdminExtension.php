<?php

namespace Elao\Bundle\MicroAdminBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ElaoMicroAdminExtension extends Extension
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

        $loaderDefinition = $container->getDefinition('elao_micro_admin.routing_loader');
        $actions          = $this->getActions($container);

        foreach ($config['administrations'] as $name => $administration) {

            $managerId         = sprintf('model_manager.%s', $name);
            $managerDefinition = new DefinitionDecorator($administration['manager']);
            $managerDefinition->addArgument($administration['model']);
            $container->setDefinition($managerId, $managerDefinition);

            foreach ($actions as $alias => $id) {

                if (!array_key_exists($alias, $actions)) {
                    throw new Exception(sprintf('Unkown action "%s"', $alias));
                }

                $serviceId        = sprintf('admin_action.%s.%s', $name, $alias);
                $actionDefinition = new DefinitionDecorator($actions[$alias]);
                $actionDefinition->addMethodCall('setModelManager', [new Reference($managerId)]);

                $container->setDefinition($serviceId, $actionDefinition);

                $this->createRoute($loaderDefinition, $serviceId, $name, $alias);
            }
        }
    }

    /**
     * Create route
     *
     * @param Definition $loaderDefinition
     * @param string $classname
     * @param string $name
     * @param string $alias
     * @param array $action
     */
    protected function createRoute(Definition $loaderDefinition, $serviceId, $name, $alias, array $action = [])
    {
        $loaderDefinition->addMethodCall(
            'addRoute',
            [
                $this->getValue($action, 'name', sprintf('%s_%s', $name, $alias)),
                $this->getValue($action, 'pattern', sprintf('/%s/%s', $name, $alias)),
                $this->getValue($action, 'controller', sprintf('%s:getResponse', $serviceId)),
                $this->getValue($action, 'parameters', []),
                $this->getValue($action, 'requirements', [])
            ]
        );
    }

    /**
     * Get actions
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    protected function getActions(ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds('elao_micro_admin.action');
        $actions  = [];

        foreach ($services as $id => $attributes) {
            $actions[$attributes[0]['alias']] = $id;
        }

        return $actions;
    }

    /**
     * Get value
     *
     * @param array $config
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getValue(array $config, $key, $default = null)
    {
        return isset($action[$key]) ? $action[$key] : $default;
    }
}
