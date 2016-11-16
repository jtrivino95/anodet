<?php

namespace Anodet\Core\Value;

use Monolog\Logger;

class Decision
{
    private $message;
    private $level;

    /**
     * @param $message
     * @param $level
     */
    public function __construct($message, $level)
    {
        $this->message = $message;
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getChannel()
    {
        $morning = new \DateTime('today 9am');
        $evening = new \DateTime('today 17pm');
        $now = new \DateTime();
        if ($this->getLevel() == Logger::EMERGENCY && ($now < $morning || $now > $evening)) {

        }
    }
}
