<?php

namespace Anodet\Core\Value;

class Action
{
    const STATUS_RAW       = 1;
    const STATUS_PARSING   = 2;
    const STATUS_PARSED    = 3;
    const STATUS_ANALYZING = 4;
    const STATUS_ANALYZED  = 5;
    const STATUS_DECIDING  = 6;
    const STATUS_DECIDED   = 7;
    const STATUS_NOTIFYING = 8;
    const STATUS_NOTIFIED  = 9;

    public $id;
    public $sourceType;
    /** @var \DateTime */
    public $datetime;
    public $status = self::STATUS_RAW;
    public $detail;
    public $behavior;
    public $result;

    public static function validate($status)
    {
        if (!in_array($status, range(1, 9))) {
            throw new \LogicException(sprintf('Invalid status "%s" provided', $status));
        }
    }

    public function serialize()
    {
        return [
            'sourceType' => $this->sourceType,
            'datetime' => $this->datetime->format('Y-m-d H:i:s'), // Datetime of what??
//            'status'       => $this->status,
            'detail' => ($this->detail),
            'behavior' => ($this->behavior),
            'result' => ($this->result),
        ];
    }

    public function unserialize($data)
    {
//        $this->id =             array_key_exists('id',$data) ? $data['id'] : $this->id;
//        $this->datetime =       new \DateTime($data['datetime']);
        $this->datetime = new \DateTime();
//        $this->status =         (int) $data['status'];
        $this->detail = array_key_exists('detail', $data) ? ($data['detail']) : $this->detail;
        $this->behavior = array_key_exists('behavior', $data) ? ($data['behavior']) : $this->behavior;
        $this->result = array_key_exists('result', $data) ? ($data['result']) : $this->result;
    }
}
