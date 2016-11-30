<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/22/16
 * Time: 7:43 PM
 */

namespace Anodet\Core\Implementations\Transporters;



class UptimeTransporter extends BaseTransporter
{

    private $hasBeenRun = false;

    public function hasNext()
    {
        return !$this->hasBeenRun;
    }

    public function getNext()
    {
        $this->hasBeenRun = true;
        $data = shell_exec("uptime");
        $regex = '
                /^\s*
                (?<time>[0-9]+:[0-9]+:?[0-9]+)
                [a-z, ]*
                ((?<uptime_min>[0-9]+\smin)|(?<uptime>[0-9]+:[0-9]+))
                ,.*(?<=:)\s
                (?<load_avg1>[0-9]+.[0-9]+)
                ,\s
                (?<load_avg2>[0-9]+.[0-9]+)
                ,\s
                (?<load_avg3>[0-9]+.[0-9]+)
                $/x
        ';
        $clean = $this->parseWithRegex($data, $regex);
        $newAction = $this->createAction($clean);
        return $newAction;
    }

    public function prepare()
    {
    }
}