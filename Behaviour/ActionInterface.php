<?php

namespace Elao\Bundle\AdminBundle\Behaviour;

use Elao\Bundle\AdminBundle\Behaviour\ActionInterface;

/**
 * Action interface
 */
interface ActionInterface
{
    /**
     * Return the http response
     *
     * @return Doctrine\Component\HttpKernel\Response
     */
    public function getResponse();
}
