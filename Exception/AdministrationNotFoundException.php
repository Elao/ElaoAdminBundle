<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2016 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Exception;

use Exception;

/**
 * Administration not found exception
 */
class AdministrationNotFoundException extends Exception
{
    /**
     * Message format
     *
     * @var string
     */
    const MESSAGE_FORMAT = 'Unkown administration "%s", availables administrations are: %s.';

    /**
     * Create an class AdministrationNotFoundException extends Exception
     *
     * @param string $name
     * @param array $administratios
     *
     * @return
     */
    public static function create($name, array $administrations, $code = 0, Exception $previous = null)
    {
        $message = sprintf(static::MESSAGE_FORMAT, $name, implode(', ', $administrations));

        return new AdministrationNotFoundException($message, $code, $previous);
    }
}
