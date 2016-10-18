<?php

namespace Elao\Bundle\AdminBundle\DependencyInjection\Action\Factory;

use Elao\Bundle\AdminBundle\Behaviour\ActionFactoryInterface;
use Elao\Bundle\AdminBundle\Utils\Word;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Abstract Action Factory
 */
abstract class ActionFactory implements ActionFactoryInterface
{
    /**
     * Configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Route configuration
     *
     * @var array
     */
    protected $route;

    /**
     * Security restriction
     *
     * @var null|string
     */
    protected $security = null;

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function processConfig(array $rawConfig, array $administration, $name, $alias)
    {
        $config = array_merge(
            $administration,
            $this->processRawConfig($rawConfig, $this->getTokens($name, $alias)),
            ['name' => $name, 'alias' => $alias]
        );

        $this->route = $config['route'];
        $this->security = isset($config['security']) ? $config['security'] : null;
        $this->config = array_diff_key($config, array_flip(['route', 'security']));
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurity() {
        return $this->security;
    }

    /**
     * {@inheritdoc}
     */
    public function configureAction(Definition $definition)
    {
        // Configure your action service definition
    }

    /**
     * Dynamize configuration with tokens
     *
     * @param array $config
     * @param array $tokens
     *
     * @return array
     */
    protected function processRawConfig(array $config, array $tokens)
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

    /**
     * Get remplacmeent tokens
     *
     * @param string $name The name of the model
     * @param string $action The alias of the action
     *
     * @return array
     */
    protected function getTokens($name, $alias)
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
