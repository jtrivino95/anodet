<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;

interface Transporter extends Module
{
    /**
     * Operations before start processing the stream
     */
    public function prepare();

    /**
     * Fetches data from external stream and generates array of actions
     * @return Action[]
     */
    public function fetch();

}
