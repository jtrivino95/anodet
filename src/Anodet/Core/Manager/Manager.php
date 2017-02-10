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
use Anodet\Core\Contracts\Transporter;
use Anodet\Core\Value\Action;
use Anodet\Core\Value\Notification;
use Anodet\Core\Value\Result;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Manager
{
    /** @var  Builder */
    private $builder;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(Builder $builder, LoggerInterface $logger)
    {
        $this->builder = $builder;
        $this->logger = $logger;
    }

    public function run()
    {

        foreach ($this->builder->getModuleInstances() as $moduleInstance) {

            try {

                $actions = $this->runTransporter($moduleInstance);

                $results = $this->runAnalyzers($moduleInstance, $actions);

                $notifications = $this->runDeciders($moduleInstance, $results);

                $this->sendNotifications($notifications);

                $this->builder->saveConfig();

            } catch (\Exception $e) {

                $this->logger->critical($e->getMessage());

            }
        }


    }

    /**
     * @return Action[]
     */
    private function runTransporter($moduleInstance)
    {

        $actions = [];

        /** @var Transporter $transporter */
        $transporter = $this->builder->build($moduleInstance, 'transporter');

        if (sizeof($transporter) > 1) $this->logger->warning("Error on " . $moduleInstance['module_code'] . ": only 1 transporter per code allowed. Using " . get_class($transporter[0]) . "\n");

        $transporter = $transporter[0];

        $transporter->prepare();

        foreach ($transporter->fetch() as $action) {

            $actions[] = $action;

        }

        return $actions;

    }

    /**
     * @param $actions Action[]
     * @return Result[]
     */
    private function runAnalyzers($moduleInstance, $actions)
    {

        $results = [];

        /** @var Analyzer[] $analyzers */
        $analyzers = $this->builder->build($moduleInstance, 'analyzer');

        foreach ($analyzers as $analyzer) {

            $supportedActions = [];

            foreach ($actions as $action) {
                if ($action && $analyzer->supports($action)) {
                    $supportedActions[] = $action;
                }
            }

            foreach ($analyzer->analyze($supportedActions) as $result) {
                $results[] = $result;
            }


        }

        return $results;

    }


    /**
     * @param $moduleInstance
     * @param $results
     * @return Notification[]
     */
    private function runDeciders($moduleInstance, $results)
    {

        $notifications = [];

        $deciders = $this->builder->build($moduleInstance, 'decider');

        /** @var Decider $decider */
        foreach ($deciders as $decider) {

            foreach ($results as $result) {

                if ($decider->supports($result)) {

                    if ($notification = $decider->decide($result)) {
                        $notifications[] = $notification;
                    }

                }

            }

        }

        return $notifications;

    }

    /**
     * @param $notifications Notification[]
     * @throws \Exception
     */
    private function sendNotifications($notifications)
    {

        foreach ($notifications as $notification) {
            $this->builder->getNotifier($notification->getChannel())->notify($notification);
        }

    }

}