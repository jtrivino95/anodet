<?php

namespace Anodet\Core\Manager;

use Anodet\Core\Contracts\Analyzer;
use Anodet\Core\Database\Database;
use Anodet\Core\Value\Action;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AnalyzerManager
{
    /** @var Database */
    private $database;

    /** @var Analyzer[] */
    private $analyzers = [];

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

    public function analyze($max = 100)
    {
        $rawActions = $this->database->consume(Action::STATUS_PARSED, $max);
        foreach ($rawActions as $action) {
            foreach ($this->analyzers as $analyzer) {
                if ($analyzer->supports($action)) {
                    $analyzer->analyze($action);
                    if (!$action->categoryHash) {
                        $this->logger->warning(sprintf(
                            'Analyzer with name %s did not fill up the behavior for an action that it supports',
                            $analyzer->getName()
                        ));
                        continue(2);
                    }
                    $action->status = $action::STATUS_ANALYZED;
                    $this->database->updateAction($action);

                    continue(2);
                }
            }
        }
    }
}
