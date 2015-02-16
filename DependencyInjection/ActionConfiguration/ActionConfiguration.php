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

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\Administration;
use Elao\Bundle\AdminBundle\DependencyInjection\Model\Action;

/**
 * Handle routing configuration
 */
abstract class ActionConfiguration implements ConfigurationInterface
{
    /**
     * Action
     *
     * @var Action
     */
    protected $action;

    /**
     * Service Id
     *
     * @var string
     */
    protected $serviceId;

    /**
     * Constructor
     *
     * @param Action $action
     * @param string $serviceId
     */
    public function __construct(Action $action, $serviceId)
    {
        $this->action    = $action;
        $this->serviceId = $serviceId;
    }

    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        return $this->buildConfiguration();
    }

    /**
     * Build
     */
    protected function buildConfiguration()
    {
        $treeBuilder       = new TreeBuilder;
        $parametersBuilder = new TreeBuilder;
        $rootNode          = $treeBuilder->root('options');

        $rootNode
            ->children()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRouteName())
                        ->end()
                        ->scalarNode('pattern')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRoutePattern())
                        ->end()
                        ->scalarNode('controller')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRouteController())
                        ->end()
                        ->arrayNode('parameters')
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('requirements')
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('methods')
                            ->validate()
                            ->ifNotInArray(['GET', 'PUT', 'POST', 'DELETE'])
                                ->thenInvalid('Invalid http method "%s"')
                            ->end()
                            ->prototype('scalar')->end()
                            ->defaultValue($this->getRouteMethods())
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('security')
                    ->info('Add a security expression to secure the action. See accepted format: http://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/security.html')
                    ->example("has_role('ROLE_ADMIN')")
                ->end()
                ->append(
                    $this->configureParametersNode($parametersBuilder->root('parameters'))
                )
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Configure parameters node
     *
     * @param NodeParentInterface $node
     */
    protected function configureParametersNode(NodeParentInterface $node)
    {
        $tree = $node->addDefaultsIfNotSet()->children();

        return $this->buildParametersTree($tree)->end();
    }

    /**
     * Build parameters tree
     *
     * @param NodeParentInterface $node
     */
    protected function buildParametersTree(NodeParentInterface $node)
    {
        return $node;
    }

    /**
     * Get default name for route dynamically
     *
     * @return string
     */
    abstract protected function getRouteName();

    /**
     * Get default pattern for route dynamically
     *
     * @return string
     */
    abstract protected function getRoutePattern();

    /**
     * Get default controller for route dynamically
     *
     * @return string
     */
    abstract protected function getRouteController();

    /**
     * Get default methods for route dynamically
     *
     * @return array
     */
    protected function getRouteMethods()
    {
        return [];
    }
}
