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

/**
 * Notifier Interface
 */
interface NotifierInterface
{
    /**
     * Notify success
     *
     * @param string $message
     */
    public function notifySuccess($message);

    /**
     * Notify error
     *
     * @param string $message
     */
    public function notifyError($message);

    /**
     * Notify warning
     *
     * @param string $message
     */
    public function notifyWarning($message);

    /**
     * Notify notice
     *
     * @param string $message
     */
    public function notifyNotice($message);
}
