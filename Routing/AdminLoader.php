<?php

namespace  Elao\Bundle\MicroAdminBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Elao\Bundle\MicroAdminBundle\Factory\AdministrationFactory;


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

    /*protected $routes = [
        'index'  => [
            'name'    => '%s',
            'pattern' => '/%s',
        ],
        'create' => [
            'name'    => '%s_create',
            'pattern' => '/%s/new',
        ],
        'read'   => [
            'name'    => '%s_read',
            'pattern' => '/%s/{id}',
        ],
        'update' => [
            'name'    => '%s_update',
            'pattern' => '/%s/{id}/update',
        ],
        'delete' => [
            'name'    => '%s_delete',
            'pattern' => '/%s/{id}/delete',
        ],
    ];*/

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
     * @param string $pattern
     * @param array $controller
     */
    public function addRoute($name, $pattern, $controller, $parameters = [], $requirements = [])
    {
        $this->routes->add(
            $name,
            new Route(
                $pattern,
                array_merge(
                    ['_controller' => $controller],
                    $parameters
                ),
                $requirements
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