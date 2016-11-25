<?php

namespace Anodet\Core\Implementations\Transporters;

use Anodet\Core\Value\Action;
use Anodet\Core\Contracts\Transporter;

abstract class BaseTransporter implements Transporter
{
    protected $rawData;

    /**
     * @param $regex
     * @return array $parsed Parsed values
     */
    protected function parseWithRegex($regex)
    {

        preg_match_all($regex, $this->rawData, $matches);

        // Restructure the array
        foreach ($matches as $field => $value) {
            if (is_int($field))
                continue; // Avoid numeric fields
            foreach ($value as $i => $match) {
                if (strlen($match) != 0) // avoid empty values
                    $parsed[$i][$field] = $match;
            }
        }

        return $parsed;
    }

    protected function createAction($detail)
    {
        $action = new Action();
        $action->unserialize([
            'detail' => $detail,
        ]);

        return $action;
    }

}
