<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;

interface Parser
{
    /**
     * @param Action $action
     * @return bool
     */
    public function supports(Action $action);

    /**
     * @param Action $action
     */
    public function parse(Action $action);

    /**
     * @return string
     */
    public function getName();
}
