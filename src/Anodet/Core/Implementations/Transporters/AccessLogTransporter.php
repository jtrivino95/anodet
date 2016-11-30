<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 23/11/16
 * Time: 18:52
 */

namespace Anodet\Core\Implementations\Transporters;

class AccessLogTransporter extends LogRotateTransporter
{
//    private $lastFetchedLine = 0;
//    private $path;
//
//    public function fetch()
//    {
//        echo("WARNING, DUE TO ROTATION THE FILE CAN CHANGE\n");
//        if (!$this->path) {
//            throw new \Exception("UNDEFINED FILE!");
//        }
//
//        $lastFileLine = `wc -l $this->path | cut -d " " -f1`;
//        $lastFetchedLine = $this->lastFetchedLine;
//        $rowsToFetch = $lastFileLine - $lastFetchedLine;
//
//        $this->lastFetchedLine = $lastFileLine;
//
//
//        $this->rawData = `tail -n $rowsToFetch $this->path`;
//
//        $regex = '/
//        (?<remote_user>[0-9.]+)\s
//        (?<hyphen>[\S]+)\s
//        (?<userid>[\S]+)\s
//        \[(?<date>.*?)\]\s
//        \"(?<request>.*?)\"\s
//        (?<response>[0-9]+)\s
//        (?<response_size>[0-9]+)
//        (\s\"(?<referer>.*?)\"\s)*
//        (\"(?<browser_info>.*?)\")*
//        /mx';
//
//        $parsed = $this->parseWithRegex($regex);
//
//        foreach ($parsed as $record) {
//            $actions[] = $this->createAction($record);
//        }
//
//        return $actions;
//    }
//
//    /**
//     * @param mixed $path
//     */
//    public function setPath($path)
//    {
//        $this->path = $path;
//    }


}