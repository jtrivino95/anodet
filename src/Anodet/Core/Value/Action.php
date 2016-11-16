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
    public $categoryHash;
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
            'categoryHash' => $this->categoryHash,
            'datetime'     => $this->datetime->format('Y-m-d H:i:s'),
            'status'       => $this->status,
            'detail'       => serialize($this->detail),
            'behavior'     => serialize($this->behavior),
            'result'       => serialize($this->result),
        ];
    }

    public function unserialize($data)
    {
        $this->id = $data['id'];
        $this->categoryHash = $data['categoryHash'];
        $this->datetime = new \DateTime($data['datetime']);
        $this->status = (int) $data['status'];
        $this->detail = $data['detail'] ? unserialize($data['detail']) : null;
        $this->behavior = $data['behavior'] ? unserialize($data['behavior']) : null;
        $this->result = $data['result'] ? unserialize($data['result']) : null;
    }
}
