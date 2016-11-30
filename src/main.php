<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/18/16
 * Time: 10:00 AM
 */

namespace Anodet;

use Anodet\Core\Database\Database;
use Anodet\Core\Value\Source;

require_once __DIR__ . "/../vendor/autoload.php";


$db = new Database(['host' => 'localhost', 'user' => 'root', 'password' => '', 'name' => 'anodet']);


$sourceProperties = $db->getSourcesProperties()[0];
$source = new Source();
$source->unserialize($sourceProperties);


$alt = $source->getTransporter();
$actionsList = $alt->fetch();
//var_dump($actionsList[0]->detail);
var_dump($actionsList);

?>