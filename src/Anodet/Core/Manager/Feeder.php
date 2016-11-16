<?php

namespace Anodet\Core\Manager;

use Anodet\Core\Contracts\Transporter;
use Anodet\Core\Database\Database;
use Anodet\Core\Value\Action;
use Anodet\Core\Value\Config;

class Feeder
{
    /** @var Transporter */
    private $transporter;

    /** @var Database */
    private $database;

    /**
     * @param Transporter $transporter
     * @param Database $database
     */
    public function __construct(Transporter $transporter, Database $database)
    {
        $this->transporter = $transporter;
        $this->database = $database;
    }

    public function fetch()
    {
        $fetchedLogs = $this->fetchLogs();

        $this->database->addActions($fetchedLogs);
    }

    /**
     * @return Action[]
     */
    private function fetchLogs()
    {
        $nextFetch = new \DateTime('+1 second');
        $config = $this->database->getConfig();

        $this->database->updateConfig(Config::CODE_LAST_FETCH, $nextFetch);

        $to = $nextFetch;
        $to->modify('-1 second');
        $from = $config->lastFetch;

        return $this->transporter->fetch($from, $to);
    }
}
