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
     * @param Action $action
     * @throws \Simplon\Mysql\MysqlException
     */
    public function addAction(Action $action)
    {
        $this->connection->insert('action', [
            'status' => $action->status,
            'detail' => serialize($action->detail)
        ]);
    }

    public function updateAction(Action $action)
    {
        $this->connection->update('action', ['id' => $action->id], $action->serialize());
    }

    /**
     * @param Action[] $actions
     * @throws \Simplon\Mysql\MysqlException
     */
    public function addActions(array $actions)
    {
        $actionsArray = [];
        foreach ($actions as $action) {
            $actionsArray[] = [
                'status' => $action->status,
                'detail' => serialize($action->detail)
            ];
        }

        $this->connection->insertMany('action', $actionsArray);
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
     * @param int $status
     * @param int $max
     * @return Action[]
     * @throws \Simplon\Mysql\MysqlException
     */
    public function consume($status, $max = 100)
    {
        Action::validate($status);

        $this->connection->executeSql(
            sprintf(
                'UPDATE `action` SET status = status + 1 WHERE status = %d AND ( SELECT @ids := CONCAT_WS(",", id, @ids)) LIMIT %d',
                $status,
                $max
            )
        );

        $ids = $this->connection->fetchColumn('SELECT @ids AS ids');

        $rows = $this->connection->fetchRowMany(sprintf('SELECT * FROM action WHERE id IN (%s)', $ids));

        $actions = [];
        foreach ($rows as $row) {
            $action = new Action();
            $action->unserialize($row);

            $actions[] = $action;
        }

        return $actions;
    }

    /**
     * @param string $code
     * @param mixed  $value
     * @throws \Simplon\Mysql\MysqlException
     */
    public function updateConfig($code, $value)
    {
        $this->connection->update('config', ['code' => $code], ['value' => $value]);
    }
}
