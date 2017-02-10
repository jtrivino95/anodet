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

class DiskSpaceTransporter extends GraylogTransporter
{

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

        $data = $this->fetchFromGraylog();

        $regex = '/^(?<log_message>\w+\s\w+)(\s-\s)(free\sspace: )(?<path>[^\s]+)(\s[^\s]+)(\s[^\s]+)(\s\()(?<free_space>[0-9]+)(%\sinode=[0-9]+%\)):$/m';

        $skipHeader = true;
        foreach ($data as $row) {

            if ($skipHeader) {
                $skipHeader = false;
            } else {

                if (isset($row[0])) {

                    $strDate = $row[0];
                    $msg = $row[1];
                    $hostname = $row[2];

                    $row = $this->parseWithRegex($msg, $regex);

                    $datetime = new \DateTime($strDate);

                    $actions[] = new Action('disk_space', ['path' => $row['path'], 'free_space' => $row['free_space'], 'hostname' => $hostname, 'time' => $datetime]);
                }

            }
        }
        return $actions;

    }


}