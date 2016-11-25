<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Behaviour;

use Symfony\Component\HttpFoundation\Request;

/**
 * Choose which route to redirect to after actions are performed
 */
interface RouteResolverInterface
{
    /**
     * Add action route
     *
     * @param string $name Administration name
     * @param string $alias Action alias
     * @param array $route Route configuration
     */
    public function addRoute($name, $alias, array $route);

    /**
     * Get route by action
     *
     * @param string $name Administration name
     * @param string $alias Action alias
     *
     * @return array
     */
    public function getRoute($name, $alias);

    /**
     * Get url for the given action
     *
     * @param string $name Administration name
     * @param string $alias Action alias
     * @param array $parameters Route parameters
     * @param array $context Context
     *
     * @return string
     */
    public function getUrl($name, $alias, array $parameters = [], array $context = []);
}
