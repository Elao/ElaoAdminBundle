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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Admin Bundle Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Administration configurators
     *
     * @var Elao\Bundle\AdminBundle\Behaviour\AdministrationConfiguratorInterface[]
     */
    private $administrationConfigurators;

    /**
     * Action factories
     *
     * @var Elao\Bundle\AdminBundle\Behaviour\ActionFactoryInterface[]
     */
    private $actionFactories;

    /**
     * Constructor.
     *
     * @param array $administrationConfigurators
     * @param array $actionFactories
     */
    public function __construct(array $administrationConfigurators, array $actionFactories)
    {
        $this->administrationConfigurators = $administrationConfigurators;
        $this->actionFactories = $actionFactories;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elao_admin');

        $administrationNodeBuilder = $rootNode
            ->children()
                ->arrayNode('administrations')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children();

        foreach ($this->administrationConfigurators as $configurator) {
            $configurator->configure($administrationNodeBuilder);
        }

        $actionNodeBuilder = $administrationNodeBuilder
                            ->arrayNode('actions')
                                ->isRequired()
                                ->disallowNewKeysInSubsequentConfigs()
                                ->cannotBeEmpty()
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children();

        foreach ($this->actionFactories as $factory) {
            $factoryNode = $actionNodeBuilder
                ->arrayNode($factory->getKey())
                ->canBeUnset()
            ;
            $factory->addConfiguration($factoryNode);
        }

        $actionNodeBuilder
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
