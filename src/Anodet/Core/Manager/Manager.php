<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 11/16/16
 * Time: 3:07 PM
 */

namespace Anodet\Core\Manager;


use Anodet\Core\Contracts\Analyzer;
use Anodet\Core\Contracts\Decider;
use Anodet\Core\Contracts\Notifier;
use Anodet\Core\Contracts\GenericParser;
use Anodet\Core\Contracts\BaseTransporter;
use Anodet\Core\Database\Database;
use Anodet\Core\Value\Config;

class Manager
{
    private $id;
    private $parser;
    private $deciders = [];
    private $analyzers = [];
    private $notifier;
    private $database;


    /** @var array Action */
    private $actions = [];
    /** @var array Action */
    private $analyzedActions = [];
    private $decisions = [];
    /** @var  Config */
    private $config;
    /** @var  \DateTime */
    private $now;


    /**
     * Manager constructor.
     * DB_Config:
     *      host
     *      user
     *      password
     *      name
     * @param $id
     * @param $db_config
     */
    public function __construct($id, $db_config)
    {
        $this->id = $id;
        $this->database = new Database($db_config);

    }

    /**
     * @param Decider $decider
     */
    public function addDecider(Decider $decider)
    {
        $this->deciders[] = $decider;
    }

    public function addAnalyzer(Analyzer $analyzer)
    {
        $this->analyzers[] = $analyzer;
    }

    /**
     * @param mixed $notifier
     */
    public function setNotifier(Notifier $notifier)
    {
        $this->notifier = $notifier;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase(Database $database)
    {
        $this->database = $database;
        $this->config = $database->getConfig();
    }

    /**
     * @param mixed $parser
     */
    public function setParser($parser)
    {
        $this->parser = $parser;
    }


    public function run(BaseTransporter $transporter)
    {

        $this->now = new \DateTime();

        if ($this->config->lastFetch == null) {
            $actions = $transporter->initialFetch();
        } else {
            $actions = $transporter->fetchData($this->config->lastFetch, $this->now); // fetch from lastFetch to now
        }

        // Now we have the actions

        /*
         * Parses raw Actions to clean Actions
         */
        foreach ($actions as $action) {
            $parses = [];

            if ($this->transporter->supports($action)) {
                $parses[] = $this->transporter->parse($action);
            }

            $this->actions = $parses;
        }

        // Now we have parsed actions

        /*
         * For each analyzer, and for each action, we get the decision.
         */
        foreach ($this->analyzers as $analyzer) {
            foreach ($actions as $action) {

                // Analyzer work
                if ($analyzer->supports($action)) {
                    $analyzer->analyze($action); // Attributes of action has been modified?
                }

                // Decider work
                foreach ($this->deciders as $decider) {
                    if ($decider->supports($action)) {
                        $this->decisions[] = $decider->decide($action);
                    }
                }

                // Notifier work
                if (sizeof($this->decisions) != 0) {
                    foreach ($this->decisions as $decision) {
                        $this->notifier->notify($decision);
                    }
                }
            }
        }

        // End of cicle


        /*
         * Set up the time of the last fetch on DB
         */
        $this->config->lastFetch = $this->now;

        $this->database->updateConfig($this->config);


    }


}