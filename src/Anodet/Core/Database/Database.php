<?php

namespace Anodet\Core\Database;

use Anodet\Core\Value\Config;
use Anodet\Core\Value\Action;
use Simplon\Mysql\Mysql as SimplonMysql;
use Symfony\Component\Config\Definition\Exception\Exception;

class Database
{
    /** @var SimplonMysql */
    private $connection;

    public function __construct(SimplonMysql $connection)
    {
        $this->connection = $connection;
    }

    /*                              *
     *                              *
     *  Framework-aimed functions   *
     *                              *
     *                              */

    /**
     * @return string[][]
     */
    public function getModuleInstances()
    {
        if ($instances = $this->connection->fetchRowMany("SELECT * FROM module_instance WHERE is_active = :active", ['active' => 1])) {

            foreach ($instances as &$instance) {
                $instance['config'] = json_decode($instance['config'], 1);
            }

            return $instances;

        } else {
            throw new Exception("No data found on database.");
        }

    }

    /**
     * @param $code
     * @return array|null
     */
    public function getData($code)
    {

        if ($data = $this->connection->fetchRow("SELECT * FROM module_instance WHERE module_code = :code", ['code' => $code])) {
            $data['config'] = json_decode($data['config'], 1);
            return $data;
        } else {
            return null;
        }

    }

    public function exists($code)
    {
        if ($this->connection->fetchRow("SELECT module_code FROM module_instance WHERE module_code = :code", ['code' => $code])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $configs Config[]
     */
    public function saveConfigs($configs)
    {
        foreach ($configs as $id => $config) {

            $this->connection->update(
                'module_instance',
                ['id' => $id],
                ['config' => json_encode($config)]
            );
        }
    }

    /**
     * @param $code
     * @return array
     */
    public function getConfigs($code)
    {
        $configs = $this->connection->fetchRowMany("SELECT id, config_class, config FROM module_instance WHERE module_code = :code", ['code' => $code]);

        if ($configs) {
            foreach ($configs as &$config) {
                $config['config'] = json_decode($config['config'], 1);
            }
        } else {
            $configs = [];
        }

        return $configs;
    }



    /*                          *
     *                          *
     *  User-aimed functions    *
     *                          *
     *                          */

    /**
     * @param Action $action
     */
    public function saveAction(Action $action)
    {
        $this->connection->insert('action', $action->serialize());
    }

    /**
     * @param $code string Transporter code
     * @return Action[]
     */
    public function getActionsDetails($code)
    {
        $decodedDetails = [];

        $actions = $this->connection->fetchRowMany(
            "SELECT detail FROM action WHERE category = '" . $code . "'"
        );

        foreach ($actions as $action) {
            $decodedDetails[] = json_decode($action['detail']);
        }

        return $decodedDetails;
    }

    /**
     * @param $code string Transporter code
     * @param \DateTime $datetime
     * @return Action[]
     */
    public function getActionsDetailsSince($code, \DateTime $datetime)
    {
        $decodedDetails = [];

        $actions = $this->connection->fetchRowMany('SELECT detail FROM action WHERE datetime > :datetime AND code = :code', ['datetime' => $datetime->format('Y-m-d H:i:s'), 'code' => $code]);

        if ($actions) {

            foreach ($actions as $action) {
                $decodedDetails[] = json_decode($action['detail'], true);
            }
        }

        return $decodedDetails;
    }
}
