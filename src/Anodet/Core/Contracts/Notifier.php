<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Notification;

interface Notifier
{
    const NOTIFIERS = [
        'slack' => 'slack.notifier',
    ];

    /**
     * Send the decision info via $decision->getChannel()
     * @param Notification $notification
     */
    public function notify(Notification $notification);

}
