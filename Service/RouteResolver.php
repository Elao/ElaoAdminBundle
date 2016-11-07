<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Elao\Bundle\AdminBundle\Behaviour\RouteResolverInterface;
use Elao\Bundle\AdminBundle\Exception\ActionNotFoundException;
use Elao\Bundle\AdminBundle\Exception\AdministrationNotFoundException;

/**
 * Rotue resolver
 */
class RouteResolver implements RouteResolverInterface
{
    /**
     * The router
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Available actions
     *
     * @var array
     */
    protected $actions;

    /**
     * Constructor
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->actions = [];
    }

    /**
     * {@inheritdoc}
     */
    public function addRoute($name, $alias, array $route)
    {
        if (!isset($this->actions[$name])) {
            $this->actions[$name] = [];
        }

        $this->actions[$name][$alias] = $route;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute($name, $alias)
    {
        if (!isset($this->actions[$name])) {
            throw AdministrationNotFoundException::create($name, array_keys($this->actions));
        }

        if (!isset($this->actions[$name][$alias])) {
            throw ActionNotFoundException::create($name, $alias, array_keys($this->actions[$name]));
        }

        return $this->actions[$name][$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($name, $alias, array $parameters = [])
    {
        $route = $this->getRoute($name, $alias);

        return $this->getRouteUrl(
            $route['name'],
            array_merge(
                $route['parameters'],
                $parameters
            )
        );
    }

    /**
     * Get route Url
     *
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    protected function getRouteUrl($name, array $parameters = [])
    {
        return $this->router->generate($name, $parameters);
    }
}
