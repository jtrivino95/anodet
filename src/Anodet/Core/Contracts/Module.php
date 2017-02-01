<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 13/01/17
 * Time: 12:56
 */

namespace Anodet\Core\Contracts;

use Anodet\Core\Value\Config;

interface Module
{
    const TYPES = ['transporter', 'analyzer', 'decider'];

    /**
     * Receives the specific config object of the code of this module
     * @param Config $config
     */
    public function setConfig(Config $config);
}