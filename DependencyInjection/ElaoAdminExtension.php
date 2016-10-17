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

use Elao\Bundle\AdminBundle\DependencyInjection\Action\Factory\ActionFactory;
use Elao\Bundle\AdminBundle\Utils\Word;
use Exception;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ElaoAdminExtension extends Extension
{
    /**
     * Action factories
     *
     * @var array
     */
    private $factories = [];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');
        $loader->load('events.xml');

        $this->createAdministrations($config['administrations'], $container);
    }

    /**
     * Get configuration
     *
     * @param array $config
     * @param ContainerBuilder $container
     *
     * @return Configuration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->factories);
    }

    /**
     * Add action factory
     *
     * @param ActionFactoryInterface $factory
     */
    public function addActionFactory(ActionFactory $factory)
    {
        $this->factories[$factory->getKey()] = $factory;
    }

    /**
     * Create administrations
     *
     * @param array $administrations
     * @param ContainerBuilder $container
     */
    protected function createAdministrations(array $administrations, ContainerBuilder $container)
    {
        $routeLoader = $container->getDefinition('elao_admin.routing_loader');
        $securityListener = $container->getDefinition('elao_admin.event.subscriber.security');
        $routeResolver = $container->getDefinition('elao_admin.route_resolver');

        foreach ($administrations as $name => $config) {
            foreach ($config['actions'] as $alias => $action) {
                foreach ($action as $type => $rawConfig) {
                    $factory = $this->factories[$type];

                    $serviceId = sprintf('action.%s.%s', Word::lowerCase($name), Word::lowerCase($alias));

                    $actionConfig = $this->processRawConfig($rawConfig, $factory->getTokens($name, $alias));
                    $actionConfig['route']['controller'] = sprintf('%s:getResponse', $serviceId);
                    $actionConfig['name'] = $name;
                    $actionConfig['alias'] = $alias;

                    $definition = new DefinitionDecorator($factory->getServiceId());

                    $factory->configureAction($definition, $actionConfig, $name, $alias);

                    $container->setDefinition($serviceId, $definition);

                    $routeLoader->addMethodCall('addRoute', $actionConfig['route']);
                    $routeResolver->addMethodCall('addRoute', [$name, $alias, $actionConfig['route']]);

                    if (isset($actionConfig['security'])) {
                        $securityListener->addMethodCall('setRouteSecurity', [
                            $actionConfig['route']['name'],
                            $actionConfig['security']
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Dynamize configuration with tokens
     *
     * @param array $config
     * @param array $tokens
     *
     * @return array
     */
    private function processRawConfig(array $config, array $tokens)
    {
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $config[$key] = $this->processRawConfig($value, $tokens);
            } elseif (is_string($value)) {
                $config[$key] = str_replace(array_keys($tokens), array_values($tokens), $value);
            }
        }

        return $config;
    }
}
