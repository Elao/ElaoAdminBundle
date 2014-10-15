<?php

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
        $serviceActions   = $this->getActions($container);

        foreach ($config['administrations'] as $name => $administration) {

            $managerId         = sprintf('model_manager.%s', $name);
            $managerDefinition = new DefinitionDecorator($administration['manager']);

            $managerDefinition->addArgument($administration['model']);
            $container->setDefinition($managerId, $managerDefinition);

            foreach ($administration['actions'] as $alias => $actionConfig) {

                if (!array_key_exists($alias, $serviceActions)) {
                    throw new Exception(sprintf(
                        'Unkown action "%s", availables actions are: %s',
                        $alias,
                        join(', ', array_keys($serviceActions))
                    ));
                }

                $parent           = $serviceActions[$alias];
                $serviceId        = sprintf('admin_action.%s.%s', $name, $alias);
                $configuration    = new $parent['configuration']($name, $alias, $serviceId);
                $actionConfig     = $this->processConfiguration($configuration, ['action' => $actionConfig]);
                $actionDefinition = new DefinitionDecorator($parent['id']);

                $actionDefinition->addMethodCall('setModelManager', [new Reference($managerId)]);
                $container->setDefinition($serviceId, $actionDefinition);
                $loaderDefinition->addMethodCall('addRoute', $actionConfig['route']);
            }
        }
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
        $services = $container->findTaggedServiceIds('elao_admin.action');
        $actions  = [];

        foreach ($services as $id => $tags) {
            foreach ($tags as $attributes) {
                $actions[$attributes['alias']] = [
                    'id'            => $id,
                    'alias'         => $attributes['alias'],
                    'configuration' => $attributes['configuration']
                ];
            }
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
        return isset($config[$key]) ? $config[$key] : $default;
    }
}
