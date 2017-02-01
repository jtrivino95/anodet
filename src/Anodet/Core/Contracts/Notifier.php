<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Notification;

interface Notifier
{
    const NOTIFIERS = [
        'slack' => 'slack.notifier',
        'mail' => 'mail.notifier',
    ];

    /**
     * Send the decision info via $decision->getChannel()
     * @param Notification $decision | null
     * @return void
     */
    public function notify(Notification $decision);

}
