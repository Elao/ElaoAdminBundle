<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Elao\Bundle\AdminBundle\Behaviour\NotifierInterface;

/**
 * Flash Message Notifier
 */
class FlashMessageNotifier implements NotifierInterface
{
    /**
     * Success notify type
     */
    const SUCCESS = 'success';

    /**
     * Error notify type
     */
    const ERROR = 'error';

    /**
     * Warning notify type
     */
    const WARNING = 'warning';

    /**
     * Notice notify type
     */
    const NOTICE = 'notice';

    /**
     * Session
     *
     * @var SessionInterface $session
     */
    protected $session;

    /**
     * Indject dependencies
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Notify success
     *
     * @param string $message
     */
    public function notifySuccess($message)
    {
        $this->notify(self::SUCCESS, $message);
    }

    /**
     * Notify error
     *
     * @param string $message
     */
    public function notifyError($message)
    {
        $this->notify(self::ERROR, $message);
    }

    /**
     * Notify warning
     *
     * @param string $message
     */
    public function notifyWarning($message)
    {
        $this->notify(self::WARNING, $message);
    }

    /**
     * Notify notice
     *
     * @param string $message
     */
    public function notifyNotice($message)
    {
        $this->notify(self::NOTICE, $message);
    }

    /**
     * Notify
     *
     * @param string $type
     * @param string $message
     */
    private function notify($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
