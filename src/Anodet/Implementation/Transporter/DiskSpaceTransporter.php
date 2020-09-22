<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 01/02/17
 * Time: 17:09
 */

namespace Anodet\Implementation\Transporter;


use Anodet\Core\BaseImplementation\GraylogTransporter;
use Anodet\Core\Value\Action;
use Anodet\Core\Value\Config;
use Anodet\Implementation\Config\DiskSpace\DiskSpaceConfig;

class DiskSpaceTransporter extends GraylogTransporter
{
    /** @var  DiskSpaceConfig */
    private $config;

    /**
     * Operations before start processing the stream
     */
    public function prepare()
    {
        // TODO: Implement prepare() method.
    }

    /**
     * Fetches data from external stream and generates array of actions
     * @return Action[]
     */
    public function fetch()
    {
        $actions = [];

        $logs = $this->fetchFromGraylog($this->config);

        $regex = '/^(?<log_message>\w+\s\w+)(\s-\s)(free\sspace: )(?<path>[^\s]+)(\s[^\s]+)(\s[^\s]+)(\s\()(?<free_space>[0-9]+)(%\sinode=[0-9]+%\)):$/m';

        $skipHeader = true;
        foreach ($logs as $row) {

            if ($skipHeader) {
                $skipHeader = false;
                continue;
            }


            if (isset($row[0])) {

                $strDate = $row[0];
                $msg = $row[1];
                $hostname = $row[2];

                $row = $this->parseWithRegex($msg, $regex);

                $datetime = new \DateTime($strDate);

                $actions[] = new Action('disk_space', ['path' => $row['path'], 'free_space' => $row['free_space'], 'hostname' => $hostname, 'time' => $datetime]);
            }

        }

        return $actions;

    }


    /**
     * Receives the specific config object of the code of this module
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
}