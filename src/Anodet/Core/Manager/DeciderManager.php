<?php

namespace Anodet\Core\Manager;

use Anodet\Core\Contracts\Decider;
use Anodet\Core\Database\Database;
use Anodet\Core\Value\Action;
use Anodet\Core\Value\Notification;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class DeciderManager
{
    /** @var Database */
    private $database;

    /** @var Decider[] */
    private $deciders = [];

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->logger = new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function decide($max = 100)
    {
        $rawActions = $this->database->consume(Action::STATUS_PARSED, $max);
        foreach ($rawActions as $action) {
            foreach ($this->deciders as $decider) {
                if ($decider->supports($action)) {
                    $decision = $decider->decide($action);

                    $notification = new Notification();
                    $notification->message = $decision->getMessage();
                    //$notification->channel

                    $action->status = $action::STATUS_DECIDED;
                    $this->database->updateAction($action);

                    continue(2);
                }
            }
        }
    }
}
