<?php

declare(strict_types=1);

namespace App\Telegram\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class TelegramRequests
{
    private ClientManager $manager;

    public function __construct(ClientManager $manager)
    {
        $this->manager = $manager;
    }

    public function getMe(): JsonResponse
    {
        $response = $this->manager->request('GET','/bot token/getMe');
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

    public function getUpdates()
    {
        $response = $this->manager->request('GET','/bot token/getUpdates');
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

}