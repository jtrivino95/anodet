<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Decision;

interface Notifier
{
    /**
     * @param Decision $decision
     * @return void
     */
    public function notifiy(Decision $decision);

}
