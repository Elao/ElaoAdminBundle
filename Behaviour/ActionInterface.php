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

/**
 * Represent an executable action
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
