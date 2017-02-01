<?php

namespace Anodet\Core\BaseImplementation;

use Anodet\Core\Value\Action;
use Anodet\Core\Contracts\Transporter;

abstract class BaseTransporter implements Transporter
{
//    /**
//     * Creates a new Action object with field 'detail' filled
//     * @param $detail
//     * @return Action
//     */
//    protected function createAction($detail)
//    {
//        $action = new Action();
//        $action->unserialize([
//            'category' => $this->getCode(),
//            'detail' => $detail,
//        ]);
//
//        return $action;
//    }

    /**
     * Convert a bunch of raw data into an array of values
     * Important: The method will only return "tagged" matches, like \^(?<time>[0-9]+:[0-9]+:[0-9]+)$\
     * @param $rawData
     * @param $regex
     * @return array
     */
    protected function parseWithRegex($rawData, $regex)
    {

        preg_match_all($regex, $rawData, $matches);
        $parsed = [];

        // Avoid non-tagged fields and empty values
        foreach ($matches as $field => $value) {
            if (is_int($field)) // avoid non-tagged fields
                continue;
            foreach ($value as $i => $match) {
                if (strlen($match) != 0) // avoid empty values
                    $parsed[$field] = $match;
            }
        }

        return $parsed;
    }
}
