<?php

namespace Anodet\Core\Value;

class Notification
{
    /** @var  string */
    private $message;
    /** @var  integer Monolog code */
    private $level;
    /** @var  string */
    private $channel;

    /**
     * Decision constructor.
     * @param $message
     * @param $level
     * @param $channel
     */
    public function __construct($message, $level, $channel)
    {
        $this->message = $message;
        $this->level = $level;
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function getLevelString()
    {
        return \Monolog\Logger::getLevelName($this->level);
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
