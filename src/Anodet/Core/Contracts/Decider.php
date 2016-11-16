<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;
use Anodet\Core\Value\Decision;

interface Decider
{
    /**
     * @param Action $action
     * @return bool
     */
    public function supports(Action $action);

    /**
     * @param Action $action
     * @return Decision
     */
    public function decide(Action $action);

    /**
     * @return string
     */
    public function getName();
}
