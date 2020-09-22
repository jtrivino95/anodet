<?php

namespace Anodet\Core\Value;

class Action
{
    /** @var  string */
    private $code;
    /** @var \DateTime */
    private $datetime;
    /** @var  array */
    private $detail;

    /**
     * Action constructor.
     * @param string $code
     * @param array $detail
     */
    public function __construct($code, $detail)
    {
        $this->code = $code;
        $this->detail = $detail;
        $this->datetime = new \DateTime();
    }

    public function serialize()
    {
        return [
            'code' => $this->code,
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'detail' => json_encode($this->detail),
        ];
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getDetail()
    {
        return $this->detail;
    }
}
