<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 23/11/16
 * Time: 18:52
 */

namespace Anodet\Core\Implementations\Transporters;

class SimpleAccessLogTransporter extends LogRotateTransporter
{
    private $currentRowNumber = 0;
    private $file;
    private $currentRow = null;

    public function hasNext()
    {

        if (!feof($this->file)) {
            if ($this->currentRow = fgets($this->file)) {
                return true;
            }
        }
        return false;
    }

    public function getNext()
    {

        if ($this->currentRow == null) {
            return null;
        }

        $regex = '/(?<remote_user>[0-9.]+)\s(?<hyphen>[\S]+)\s(?<userid>[\S]+)\s\[(?<date>.*?)\]\s\"(?<request>.*?)\"\s(?<response>[0-9]+)\s(?<response_size>[0-9]+)(\s\"(?<referer>.*?)\"\s)*(\"(?<browser_info>.*?)\")*/m';

        $parsed = $this->parseWithRegex($this->currentRow, $regex);

        $action = $this->createAction($parsed);
        $this->currentRowNumber += 1;
        $this->currentRow = null;

        return $action;

        return $this->currentRow;
    }


    public function fetchOld()
    {
        echo("WARNING, DUE TO ROTATION THE FILE CAN CHANGE\n");
        if (!$this->path) {
            throw new \Exception("UNDEFINED FILE!");
        }

        $lastFileLine = `wc -l $this->path | cut -d " " -f1`;
        $lastFetchedLine = $this->currentRowNumber;
        $rowsToFetch = $lastFileLine - $lastFetchedLine;

        $this->currentRowNumber = $lastFileLine;


        $this->rawData = `tail -n $rowsToFetch $this->path`;

        $regex = '/
        (?<remote_user>[0-9.]+)\s
        (?<hyphen>[\S]+)\s
        (?<userid>[\S]+)\s
        \[(?<date>.*?)\]\s
        \"(?<request>.*?)\"\s
        (?<response>[0-9]+)\s
        (?<response_size>[0-9]+)
        (\s\"(?<referer>.*?)\"\s)*
        (\"(?<browser_info>.*?)\")*
        /mx';

        $parsed = $this->parseWithRegex($regex);

        foreach ($parsed as $record) {
            $actions[] = $this->createAction($record);
        }

        return $actions;
    }


}