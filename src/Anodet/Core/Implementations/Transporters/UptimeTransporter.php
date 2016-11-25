<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/22/16
 * Time: 7:43 PM
 */

namespace Anodet\Core\Implementations\Transporters;

use Anodet\Core\Value\Action;

class UptimeTransporter extends BaseTransporter
{

    public function fetch()
    {
        $this->rawData = `uptime`;

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

        $clean = $this->parseWithRegex($regex);

        $actions[] = $this->createAction($clean);

        return $actions;
    }
}