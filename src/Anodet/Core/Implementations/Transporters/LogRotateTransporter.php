<?php

namespace Anodet\Core\Implementations\Transporters;

use Anodet\Core\Value\Action;
use Anodet\Core\Contracts\Transporter;

abstract class LogRotateTransporter extends LogTransporter
{
    public function prepare()
    {

        $this->file = fopen($this->transporterSource->path, 'r') or die("Can't open file");

        while ($this->currentRowNumber < $this->transporterSource->lastRow &&
            $this->hasNext()) {
            $this->currentRowNumber += 1;
            var_dump($this->currentRow);
            $this->currentRow = null;
            // var_dump($this->currentRow);
        }

        if ($this->currentRowNumber < $this->transporterSource->lastRow) {
            die("couldn't get to next row!");
        }
    }
}
