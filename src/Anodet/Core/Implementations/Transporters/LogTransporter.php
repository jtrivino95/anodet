<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/22/16
 * Time: 4:01 PM
 */

namespace Anodet\Core\Implementations\Transporters;


use Anodet\Core\Contracts\BaseTransporter;
use Anodet\Core\Value\Action;

abstract class LogTransporter extends BaseTransporter
{

    public function fetch()
    {
        // Find Log File(s)

        // Go through each Line
        // Parse Row
        // If Row UID satisfies prev Row UID then include
        // When you get to last row, update source with new UID

    }
}