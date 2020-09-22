<?php

namespace Anodet\Core\Value;


class Result
{
    /** @var integer */
    private $code;
    /** @var array */
    private $detail;

    public function __construct($code, $detail)
    {
        $this->code = $code;
        $this->detail = $detail;
    }

    public function serialize()
    {
        return [
            'code' => $this->code,
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

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param array $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }
}