<?php

namespace Anodet\Core\Database;

use Anodet\Core\Value\Action;
use Anodet\Core\Value\Config;
use Simplon\Mysql\Mysql as SimplonMysql;

class Database
{
    /** @var SimplonMysql */
    private $connection;

    public function __construct(array $config)
    {
        $this->connection = new SimplonMysql(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['name']
        );
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        $rows = $this->connection->fetchRowMany('SELECT code, value FROM config');

        $data = [];
        foreach ($rows as $row) {
            $data[$row['code']] = $row['value'];
        }

        $config = new Config();
        $config->lastFetch = new \DateTime($data[Config::CODE_LAST_FETCH]);

        return $config;
    }

    /**
     * @param string $code
     * @param mixed $value
     * @throws \Simplon\Mysql\MysqlException
     */
    public function updateConfig($code, $value)
    {
        $this->connection->update('config', ['code' => $code], ['value' => $value]);
    }


    /**
     * @param Action $action
     */
    public function insertAction(Action $action)
    {
        foreach ($action->serialize() as $value) {
            $this->connection->insert("INSERT into Action VALUES ($value)");
        }
    }


    /**
     * @param $column
     * @param $value
     * @return Action[]
     */
    public function getActionsBy($column, $value)
    {

        $actions = [];

        $rows = $this->connection->fetchRowMany("SELECT * FROM Action WHERE $column = $value");

        foreach ($rows as $row) {
            $action = new Action();
            $action->unserialize($row);
            $actions[] = $action;
        }

        return $actions;

    }
}
