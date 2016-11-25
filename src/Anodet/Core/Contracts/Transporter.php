<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;

interface Transporter
{
    /**
     * @return Action[]
     */
    public function fetch();

}
