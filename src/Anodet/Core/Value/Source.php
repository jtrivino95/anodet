<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/22/16
 * Time: 6:33 PM
 */

namespace Anodet\Core\Value;

use Anodet\Core\Contracts\Transporter;


class Source
{

    public $id;
    public $type;
    public $transporterClassName;
    public $path;
    public $config;
    public $lastRow;
    public $lastDate;
    public $lastHash;


    public function serialize()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'transporterClass' => $this->transporterClassName,
            'path' => $this->path,
            'config' => $this->config,
            'lastRow' => $this->lastRow,
            'lastDate' => $this->lastDate,
            'lastHash' => $this->lastHash,
        ];
    }

    public function unserialize($data)
    {
        $this->id = array_key_exists('id', $data) ? ($data['id']) : $this->id;
        $this->type = array_key_exists('type', $data) ? ($data['type']) : $this->type;
        $this->transporterClassName = array_key_exists('transporterClassName', $data) ? ($data['transporterClassName']) : $this->transporterClassName;
        $this->path = array_key_exists('path', $data) ? ($data['path']) : $this->path;
        $this->config = array_key_exists('config', $data) ? ($data['config']) : $this->config;
        $this->lastRow = array_key_exists('lastRow', $data) ? ($data['lastRow']) : $this->lastRow;
        $this->lastDate = array_key_exists('lastDate', $data) ? ($data['lastDate']) : $this->lastDate;
        $this->lastHash = array_key_exists('lastHash', $data) ? ($data['lastHash']) : $this->lastHash;
    }


    /**
     * @return Transporter
     */
    public function getTransporter()
    {
        $fullClassName = 'Anodet\\Core\\Implementations\\Transporters\\' . $this->transporterClassName;
        return new $fullClassName($this);
    }


}