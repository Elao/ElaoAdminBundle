<?php

namespace Elao\Bundle\AdminBundle\DependencyInjection\Action\Factory;

use Elao\Bundle\AdminBundle\Utils\Word;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 *Abstract Action Factory
 */
abstract class ActionFactory
{
    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('route')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRouteName())
                        ->end()
                        ->scalarNode('pattern')
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRoutePattern())
                        ->end()
                        /*->scalarNode('controller')
                            ->cannotBeEmpty()
                            ->defaultValue($this->getRouteController())
                        ->end()*/
                        ->arrayNode('parameters')
                            ->defaultValue($this->getRouteParameters())
                            ->prototype('variable')->end()
                        ->end()
                        ->arrayNode('requirements')
                            ->defaultValue($this->getRouteRequirements())
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
                        ->scalarNode('host')
                            ->defaultValue($this->getRouteHost())
                        ->end()
                        ->arrayNode('schemes')
                            ->defaultValue($this->getRouteSchemes())
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('security')
                    ->info('Add a security expression to secure the action. See accepted format: http://symfony.com/doc/master/bundles/SensioFrameworkExtraBundle/annotations/security.html')
                    ->example("has_role('ROLE_ADMIN')")
                ->end()
            ->end()
        ;
    }

    public function configureAction($definition, array $config)
    {
        $parameters = array_diff_key($config, array_flip(['route', 'security']));

        $definition->addArgument($parameters);
    }

    /**
     * Get remplacmeent tokens
     *
     * @param string $name The name of the model
     * @param string $action The alias of the action
     *
     * @return array
     */
    public function getTokens($name, $alias)
    {
        return [
            '%name%' => Word::lowerCase($name, false),
            '%names%' => Word::lowerCase($name, true),
            '%Name%' => Word::camelCase($name, false),
            '%Names%' => Word::camelCase($name, true),
            '%-name-%' => Word::url($name, false),
            '%-names-%' => Word::url($name, true),
            '%alias%' => Word::lowerCase($alias),
            '%Alias%' => Word::camelCase($alias),
            '%-alias-%' => Word::url($alias),
        ];
    }

    /**
     * Get action key
     *
     * @return string
     */
    abstract public function getKey();

    /**
     * Get action key
     *
     * @return string
     */
    abstract public function getServiceId();

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
     * Get default parameters for route dynamically
     *
     * @return array
     */
    protected function getRouteParameters()
    {
        return [];
    }

    /**
     * Get default requirements for route dynamically
     *
     * @return array
     */
    protected function getRouteRequirements()
    {
        return [];
    }

    /**
     * Get default methods for route dynamically
     *
     * @return array
     */
    protected function getRouteMethods()
    {
        return [];
    }

    /**
     * Get default host for route dynamically
     *
     * @return array
     */
    protected function getRouteHost()
    {
        return '';
    }

    /**
     * Get default schemes for route dynamically
     *
     * @return array
     */
    protected function getRouteSchemes()
    {
        return [];
    }
}
