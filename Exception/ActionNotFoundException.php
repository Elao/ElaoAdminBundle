<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Exception;

use Exception;

/**
 * Action not found exception
 */
class ActionNotFoundException extends Exception
{
    /**
     * Message format
     *
     * @var string
     */
    const MESSAGE_FORMAT = 'Unkown action "%s", availables actions are: %s.';

    /**
     * Create an ActionNotFoundException
     *
     * @param string $action
     * @param array $actions
     *
     * @return ActionNotFoundException
     */
    public static function create($action, array $actions, $code = 0, Exception $previous = null)
    {
        $message = sprintf(static::MESSAGE_FORMAT, $action, implode(', ', $actions));

        return new ActionNotFoundException($message, $code, $previous);
    }
}
