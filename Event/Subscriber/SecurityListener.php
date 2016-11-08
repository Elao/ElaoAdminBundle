<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Event\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityListener implements EventSubscriberInterface
{
    /**
     * Security context
     *
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * Secured routes
     *
     * @var array
     */
    protected $routes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * Set security configuration for the given route
     *
     * @param string $route
     * @param string $expression
     */
    public function setRouteSecurity($route, $expression)
    {
        $this->routes[$route] = new Security(['expression' => $expression]);
    }

    /**
     * Set security configuration on the current request if needed
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $route   = $request->attributes->get('_route');

        if (array_key_exists($route, $this->routes)) {
            $request->attributes->set('_security', $this->routes[$route]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelRequest'];
    }
}
