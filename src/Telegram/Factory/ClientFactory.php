<?php

declare(strict_types=1);

namespace App\Telegram\Factory;

use App\Telegram\Service\ClientManager;
use GuzzleHttp\Client;

/**
 * @property Client client
 */
class ClientFactory
{
    public function __invoke(): ClientManager
    {
        return $this->client =  new ClientManager(
            [
                'base_uri' => 'https://api.telegram.org',
                'Content-Type' => 'application/json',
            ]
        );
    }

}