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

use Elao\Bundle\AdminBundle\Behaviour\ActionFactoryInterface;
use Elao\Bundle\AdminBundle\Behaviour\AdministrationConfiguratorInterface;
use Elao\Bundle\AdminBundle\Utils\Word;
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
     * Administration configurators
     *
     * @var array
     */
    private $administrationConfigurators = [];

    /**
     * Action factories
     *
     * @var array
     */
    private $actionFactories = [];

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

        if ($config['doctrine_service_repositories']) {
            $loader->load('doctrine_service_repositories.xml');
        }

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
        return new Configuration($this->administrationConfigurators, $this->actionFactories);
    }

    /**
     * Add action factory
     *
     * @param AdministrationConfiguratorInterface $configurator
     */
    public function addAdministrationConfigurator(AdministrationConfiguratorInterface $configurator)
    {
        $this->administrationConfigurators[] = $configurator;
    }

    /**
     * Add action factory
     *
     * @param ActionFactoryInterface $factory
     */
    public function addActionFactory(ActionFactoryInterface $factory)
    {
        $this->actionFactories[$factory->getKey()] = $factory;
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
                    $factory = $this->actionFactories[$type];

                    $factory->processConfig(
                        $rawConfig,
                        array_diff_key($config, array_flip(['actions'])),
                        $name,
                        $alias
                    );

                    $serviceId = sprintf('action.%s.%s', Word::lowerCase($name), Word::lowerCase($alias));
                    $definition = new DefinitionDecorator($factory->getServiceId());

                    $factory->configureAction($definition);
                    $container->setDefinition($serviceId, $definition);

                    $route = array_merge(
                        $factory->getRoute(),
                        ['controller' => sprintf('%s:getResponse', $serviceId)]
                    );

                    $routeLoader->addMethodCall('addRoute', $route);
                    $routeResolver->addMethodCall('addRoute', [$name, $alias, $route]);

                    if ($security = $factory->getSecurity()) {
                        $securityListener->addMethodCall('setRouteSecurity', [$route['name'], $security]);
                    }
                }
            }
        }
    }
}
