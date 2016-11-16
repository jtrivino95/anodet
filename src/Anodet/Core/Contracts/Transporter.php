<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;

interface Transporter
{
    /**
     * @return Action[]
     */
    public function initialFetch();

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Action[]
     */
    public function fetch(\DateTime $from, \DateTime $to);
}
