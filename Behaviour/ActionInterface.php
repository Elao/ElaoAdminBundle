<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Behaviour;

use Symfony\Component\HttpFoundation\Request;

/**
 * Represent an executable action
 */
interface ActionInterface
{
    /**
     * Return the http response
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getResponse(Request $request);
}
