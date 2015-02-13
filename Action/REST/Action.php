<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Action\REST;

use Elao\Bundle\AdminBundle\Action\Action as BaseAction;

/**
 * Abstract REST Action
 */
abstract class Action extends BaseAction
{
    /**
     * Create response
     *
     * @param array $parameters
     *
     * @return Response
     */

    protected function createResponse(array $data = [])
    {
        return new JSONResponse(serialize($data));
    }
}
