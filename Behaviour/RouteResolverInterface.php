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

use Symfony\Component\HttpFoundation\Request;

/**
 * Choose which route to redirect to after actions are performed
 */
interface RouteResolverInterface
{
    /**
     * Add action
     *
     * @param string $alias
     * @param array $route
     */
    public function addAction($alias, array $route);

    /**
     * Get action by alias
     *
     * @param string $alias
     *
     * @return array
     */
    public function getAction($alias);

    /**
     * Get url for the given action
     *
     * @param string $action
     * @param Request $request
     * @param mixed $data
     *
     * @return string
     */
    public function getUrl($action, Request $request, $data);
}
