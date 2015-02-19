<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  Elao\Bundle\AdminBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Elao\Bundle\AdminBundle\Factory\AdministrationFactory;


/**
 * Admin loader
 */
class AdminLoader implements LoaderInterface
{
    /**
     * Route type
     */
    const TYPE = 'admin';

    /**
     * Loaded
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * Action
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Construct
     */
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    /**
     * Add route
     *
     * @param string $name
     * @param string $path
     * @param array $controller
     */
    public function addRoute(
        $name,
        $path,
        $controller,
        array $parameters = [],
        array $requirements = [],
        array $methods = [],
        $host = '',
        $schemes = []
    ) {
        $this->routes->add(
            $name,
            new Route(
                $path, // Path
                array_merge(['_controller' => $controller], $parameters), // Defaults
                $requirements, // Requirements
                [], // Options
                $host, // Host
                $schemes, // Schemes
                $methods, // Methods
                '' // Condition
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if ($this->loaded) {
            throw new \RuntimeException('Admin loader already loaded');
        }

        $this->loaded = true;

        return $this->routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return static::TYPE === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
}
