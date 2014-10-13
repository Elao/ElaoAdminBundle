<?php

namespace Elao\Bundle\MicroAdminBundle\Behaviour;

use Elao\Bundle\MicroAdminBundle\Behaviour\ActionInterface;

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
