<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/18/16
 * Time: 10:00 AM
 */

namespace Anodet;

require_once __DIR__ . "/../vendor/autoload.php";;

global $argv;

$appkernel = new \AppKernel();

$function = isset($argv[1]) ? $argv[1] : null;

switch ($function) {
    case 'boot':
        $appkernel->boot();
        break;
    default:
        options();
}

function options()
{
    echo "Options:\n";
    echo "1. boot\n";
}