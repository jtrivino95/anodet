<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Implementations\Source;
use Anodet\Core\Value\Action;

interface Transporter
{
    public function prepare();

    /**
     * @return Action[]
     */
    public function fetch();

    /**
     * @return Source
     */
    public function getSource();

}
