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

    /** @var  GraylogConfig */
    protected $config;
    /** @var Logger */
    private $logger;

    private $graylog_logs = null;
    private $httpQuery;

    function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function fetchFromGraylog()
    {
        // if there are more than two instances, it is not necessary to call rest api twice
        if (!$this->graylog_logs) {

            $curl = curl_init($this->config->url);

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->config->url . '/search/universal/absolute?' . $this->httpQuery,
                CURLOPT_USERPWD => $this->config->user . ":" . $this->config->password,
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
            $this->graylog_logs = $logs;
        }

        $this->logger->info("GraylogTransporter returned " . sizeof($this->graylog_logs) . " logs of " . $this->config->interval_days . " days ago.");

        return $this->graylog_logs;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;

        if (!$this->config->query || !$this->config->fields || !$this->config->interval_days) {
            throw new \Exception("Attribute 'query', 'fields' or 'interval_days' are null. Please, specify them.");
        }

        $this->httpQuery = [];
        $this->httpQuery['from'] = ((new \DateTime('now'))->sub(new \DateInterval("P" . $this->config->interval_days . "D")))->format('Y-m-d H:i:s');
        $this->httpQuery['to'] = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $this->httpQuery['query'] = $this->config->query;
        $this->httpQuery['fields'] = $this->config->fields;
//        if($this->config->MAX_ROWS_TO_FETCH) $this->httpQuery['limit'] = $this->config->MAX_ROWS_TO_FETCH;
        $this->httpQuery = http_build_query($this->httpQuery);
    }
}