<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/18/16
 * Time: 10:00 AM
 */

namespace Anodet;

use Anodet\Core\Implementations\Transporters\UptimeTransporter;
use Anodet\Core\Implementations\Transporters\AccessLogTransporter;

require_once __DIR__ . "/../vendor/autoload.php";


$transporter1 = new UptimeTransporter();
$actions1 = $transporter1->fetch();

$transporter2 = new AccessLogTransporter();
$transporter2->setPath('/var/log/apache2/access.log'); // WARNING, DUE TO ROTATION THE FILE CAN CHANGE
$actions2 = $transporter2->fetch();

$actions = array_merge($actions1, $actions2);

var_dump($actions);

// send actions to analyzer


?>