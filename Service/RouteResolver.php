<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Elao\Bundle\AdminBundle\Behaviour\RouteResolverInterface;
use Elao\Bundle\AdminBundle\Exception\ActionNotFoundException;

/**
 * Workflow Manager
 */
class RouteResolver implements RouteResolverInterface
{
    /**
     * The router
     *
     * @var \Symfony\Component\Routing\RouterInterface
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
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router  = $router;
        $this->actions = [];
    }

    /**
     * Add action
     *
     * @param string $alias
     * @param array $route
     */
    public function addAction($alias, array $route)
    {
        $this->actions[$alias] = $route;
    }

    /**
     * Get action by alias
     *
     * @param string $alias
     *
     * @return array
     */
    public function getAction($alias)
    {
        if (!isset($this->actions[$alias])) {
            throw ActionNotFoundException::create($alias, array_keys($this->actions));
        }

        return $this->actions[$alias];
    }

    /**
     * Get url for the given action
     *
     * @param string $action
     * @param Request $request
     * @param mixed $data
     *
     * @return string
     */
    public function getUrl($action, Request $request, $data)
    {
        $action = $this->getAction($action);

        return $this->getRouteUrl(
            $action['name'],
            array_merge(
                $action['parameters'],
                ['id' => $data->getId()]
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
