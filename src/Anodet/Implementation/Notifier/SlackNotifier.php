<?php
/**
 * Created by PhpStorm.
 * User: jtrivino
 * Date: 19/12/16
 * Time: 16:51
 */

namespace Anodet\Implementation\Notifier;


use Anodet\Core\Contracts\Notifier;
use Anodet\Core\Value\Notification;
use Maknz\Slack\Client;
use Psr\Log\LoggerInterface;


class SlackNotifier implements Notifier
{
    private $client;
    private $logger;

    function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param Notification $notification
     * @return void
     */
    public function notify(Notification $notification)
    {
        $this->logger->info("Sending notification with Slack to " . $this->client->getDefaultChannel() . "\n");
        $this->client->to($this->client->getDefaultChannel())->send("Level: " . ($notification->getLevelString()) . "\n" . "Message:\n " . $notification->getMessage());
    }

}