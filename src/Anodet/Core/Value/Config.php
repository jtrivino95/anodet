<?php

namespace Anodet\Core\Value;


class Config
{
    const CONFIG_CLASSES_ROOT = 'Anodet\\Implementation\\Config';

    public function __construct(array $config)
    {
        foreach ($config as $field => $value) {
            $this->$field = $value;
        }
    }

    // todo necessary?
//    protected function setRelation(&$config, $class, $field)
//    {
//        $this->$field = new $class($config[$field]);
//        unset($config[$field]);
//    }
}