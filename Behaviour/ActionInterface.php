<?php

namespace Elao\Bundle\AdminBundle\Behaviour;

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
