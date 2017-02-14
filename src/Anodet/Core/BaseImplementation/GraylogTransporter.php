<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 18/01/17
 * Time: 17:13
 */

namespace Anodet\Core\BaseImplementation;

use Monolog\Logger;
use Anodet\Core\Value\Config;
use Anodet\Implementation\Config\GraylogConfig;

abstract class GraylogTransporter extends BaseTransporter
{
    /** @var Logger */
    private $logger;

    function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GraylogConfig $config
     * @return array
     */
    public function fetchFromGraylog(GraylogConfig $config)
    {

        $this->graylog_logs = [];
        $httpQuery = $this->makeHttpQuery($config);


        $curl = curl_init($config->url);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $config->url . '/search/universal/absolute?' . $httpQuery,
            CURLOPT_USERPWD => $config->user . ":" . $config->password,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        // Convert CSV to Array
        $logs = [];
        $lines = explode(PHP_EOL, $response);
        foreach ($lines as $line) {
            $logs[] = str_getcsv($line);
        }

        $this->logger->info("GraylogTransporter returned " . sizeof($logs) . " logs of " . $config->interval_days . " days ago.");

        return $logs;
    }

    /**
     * @param GraylogConfig $config
     * @return array
     * @throws \Exception
     */
    public function makeHttpQuery(GraylogConfig $config)
    {

        if (!$config->query || !$config->fields || !$config->interval_days) {
            throw new \Exception("Attribute 'query', 'fields' or 'interval_days' are null. Please, specify them.");
        }

        $httpQuery = [];
        $httpQuery['from'] = ((new \DateTime('now'))->sub(new \DateInterval("P" . $config->interval_days . "D")))->format('Y-m-d H:i:s');
        $httpQuery['to'] = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $httpQuery['query'] = $config->query;
        $httpQuery['fields'] = $config->fields;
//      $this->httpQuery['limit'] = $this->config->MAX_ROWS_TO_FETCH;

        return http_build_query($httpQuery);
    }

}