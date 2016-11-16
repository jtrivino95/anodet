<?php

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Action;

interface Analyzer
{
    /**
     * @param Action $action
     * @return bool
     */
    public function supports(Action $action);

    /**
     * @param Action $action
     */
    public function analyze(Action $action);

    /**
     * @return string
     */
    public function getName();
}
