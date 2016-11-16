<?php

namespace Anodet\Core\Manager;

use Anodet\Core\Contracts\Parser;
use Anodet\Core\Database\Database;
use Anodet\Core\Value\Action;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ParserManager
{
    /** @var Database */
    private $database;

    /** @var Parser[] */
    private $parsers = [];

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

    /**
     * @param Parser $parser
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @param int $max
     */
    public function run($max = 100)
    {
        $rawActions = $this->database->consume(Action::STATUS_RAW, $max);
        foreach ($rawActions as $action) {
            foreach ($this->parsers as $parser) {
                if ($parser->supports($action)) {
                    $parser->parse($action);
                    if (!$action->categoryHash) {
                        $this->logger->warning(sprintf(
                            'Parser with name %s did not fill up the hash for an action that it supports',
                            $parser->getName()
                        ));
                        continue(2);
                    }
                    $action->status = $action::STATUS_PARSED;
                    $this->database->updateAction($action);

                    continue(2);
                }
            }
        }
    }
}
