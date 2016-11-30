<?php

namespace Anodet\Core\Implementations\Transporters;

use Anodet\Core\Value\Source;
use Anodet\Core\Value\Action;
use Anodet\Core\Contracts\Transporter;

abstract class BaseTransporter implements Transporter
{
    const MAX_ROWS_TO_PARSE = 1000;

    /**
     * @var Source
     */
    protected $transporterSource;

    function __construct(Source $transporterSource)
    {
        $this->transporterSource = $transporterSource;
    }

    /**
     * @return Action[]
     */
    public function fetch()
    {
        $this->prepare();

        $actions = [];
        while ($this->hasNext()
            && count($actions) <= self::MAX_ROWS_TO_PARSE) {
            $actions[] = $this->getNext();
        }

        return $actions;
    }

    /**
     * @return boolean
     */
    abstract function hasNext();

    /**
     * @return Action
     */
    abstract function getNext();


    /**
     * @return Source
     */
    public function getSource()
    {
        return $this->transporterSource;
    }

    /**
     * @param $regex
     * @return array $parsed Parsed values
     */
    protected function parseWithRegex($rawData, $regex)
    {

        preg_match_all($regex, $rawData, $matches);
        $parsed = [];

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
