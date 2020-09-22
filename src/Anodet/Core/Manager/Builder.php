<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 10/01/17
 * Time: 19:58
 */

namespace Anodet\Core\Manager;


use Anodet\Core\Contracts\Module;
use Anodet\Core\Database\Database;
use Anodet\Core\Contracts\Notifier;
use Anodet\Core\Value\Config;
use Exception;
use Symfony\Component\DependencyInjection\Container;

class Builder
{

    /** @var  Container */
    private $container;
    /** @var  Database */
    private $database;

    /** @var  string[][][] */
    private $moduleIds;

    /** @var  Config[] */
    private $configs;

    /**
     * Builder constructor.
     * @param Container $container
     * @param Database $database
     */
    function __construct(Container $container, Database $database)
    {
        $this->container = $container;
        $this->database = $database;
    }

    /**
     * Get rows from module_instance table
     * @return \string[][]
     */
    public function getModuleInstances()
    {
        return $this->database->getModuleInstances();
    }

    /**
     * Returns all the modules $type (transporter, analyzer..) associated with a module instance
     * @param $moduleInstance string
     * @param $type string              {transporter, analyzer, decider}
     * @return Module[]
     * @throws Exception
     */
    public function build($moduleInstance, $type)
    {

        $modules = [];
        $code = $moduleInstance['module_code'];

        if (isset($this->moduleIds[$code][$type])) {

            foreach ($this->moduleIds[$code][$type] as $moduleId) {

                /** @var Module $module */
                $module = $this->container->get($moduleId);
                $module->setConfig($this->getConfigObject($moduleInstance));

                $modules[] = $module;

            }

        } else {
            throw new \Exception("No $type found with code '$code'. Add your $type(s) on anodet/app/config/modules.yml\n");
        }

        return $modules;

    }

    /**
     * @param $name string      Name of the notifier followed by '.notifier' (e.g: slack.notifier)
     * @return Notifier
     * @throws Exception
     */
    public function getNotifier($name)
    {
        $notifier = $this->container->get($name);

        if ($notifier instanceof Notifier) {

            return $notifier;

        } else {

            throw new \Exception("$name is not a notifier.");

        }
    }


    /**
     * This function is aimed to be used by the service container compiler
     * @param $code
     * @param $type
     * @param $serviceID
     */
    public function addModuleId($code, $type, $serviceID)
    {
        $this->moduleIds[$code][$type][] = $serviceID;
    }

    /**
     * @param $moduleInstance
     * @return Config
     * @throws Exception
     */
    public function getConfigObject($moduleInstance)
    {
        $instanceId = $moduleInstance['id'];
        $configClass = $moduleInstance['config_class'];
        $config = $moduleInstance['config'];

        $this->configs[$instanceId] = new $configClass($config);

        if ($this->configs[$instanceId] instanceof Config) {
            return $this->configs[$instanceId];
        } else {
            throw new Exception($configClass . " must extend Config");
        }
    }

    /**
     * Saves the current variable states of the config objects on DB
     */
    public function saveConfig()
    {
        $this->database->saveConfigs($this->configs);
        $this->configs = [];
    }
}