<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Behaviour;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Configure a type of Action
 */
interface ActionFactoryInterface
{
    /**
     * Get configuration tree key.
     *
     * @return  string
     */
    public function getKey();

    /**
     * Get config from raw config
     *
     * @param array $rawConfig Raw action configuration
     * @param array $administration Administration configuration
     * @param string $name Administration name
     * @param string $alias Action alias
     *
     * @return array
     */
    public function processConfig(array $rawConfig, array $administration, $name, $alias);

    /**
     * Get service id
     *
     * @return string
     */
    public function getServiceId();

    /**
     * Configure the action service
     *
     * @param Definition $definition
     */
    public function configureAction(Definition $definition);

    /**
     * Get route configuration
     *
     * @return array
     */
    public function getRoute();

    /**
     * Get security restriction (optional)
     *
     * @return null|string
     */
    public function getSecurity();
}
