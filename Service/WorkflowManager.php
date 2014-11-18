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

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Elao\Bundle\AdminBundle\Behaviour\WorkflowManagerInterface;

/**
 * Workflow Manager
 */
class WorkflowManager implements WorkflowManagerInterface
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
     * Get url of the given action
     *
     * @param string $action
     * @param mixed $data
     *
     * @return string
     */
    public function getUrl($action, $data)
    {
        if (!isset($this->actions[$action])) {
            throw new Exception(sprintf(
                'Unkown action "%s", available actions are: %s.',
                $action,
                implode(', ', array_keys($this->actions))
            ));
        }

        return $this->getRouteUrl(
            $this->actions[$action],
            $this->getParameters($this->actions[$action], $data)
        );
    }

    /**
     * Get parameters
     *
     * @param array $route
     * @param mixed $data
     *
     * @return array
     */
    protected function getParameters(array $route, $data)
    {
        $parameters = $route['parameters'];

        if (preg_match_all('#\{([^}]+)\}#i', $route['pattern'], $tokens)) {
            $accessor = $this->getPropertyAccessor();
            foreach ($tokens[1] as $token) {
                if (!isset($parameters[$token]) && $accessor->isReadable($data, $token)) {
                    $parameters[$token] = $accessor->getValue($data, $token);
                }
            }
        }

        return $parameters;
    }

    /**
     * Get route Url
     *
     * @param array $route
     * @param array $parameters
     *
     * @return string
     */
    protected function getRouteUrl(array $route, array $parameters = [])
    {
        return $this->router->generate($route['name'], $parameters);
    }

    /**
     * Get property accessor
     *
     * @return PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if (!isset($this->propertyAccessor)) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
