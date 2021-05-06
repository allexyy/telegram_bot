<?php

declare(strict_types=1);

namespace App\Telegram\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route ("/setWebhook")
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setWebhook(): JsonResponse
    {
        $response = $this->manager->request('POST',"/bot{$_ENV['TELEGRAM_TOKEN']}/setWebhook",[
            'json' => ['url' => "{$_ENV['HOST']}/hook"]
        ]);
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

    /**
     * @Route ("/deleteWebhook")
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteWebhook()
    {
        $response = $this->manager->request('GET',"/bot{$_ENV['TELEGRAM_TOKEN']}/deleteWebhook");
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

    /**
     * @Route ("/getWebhookInfo")
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWebhookInfo()
    {
        $response = $this->manager->request('GET',"/bot{$_ENV['TELEGRAM_TOKEN']}/getWebhookInfo");
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

    /**
     * @Route ("/sendMessage")
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(array $data)
    {
        $response = $this->manager->request('POST',"/bot{$_ENV['TELEGRAM_TOKEN']}/sendMessage", [
            'json' => $data
        ]);
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

    /**
     * @Route ("/sendPhoto")
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPhoto(array $data)
    {
        $response = $this->manager->request('POST',"/bot{$_ENV['TELEGRAM_TOKEN']}/sendPhoto", [
            'json' => $data
        ]);
        return new JsonResponse(json_decode($response->getBody()->getContents()));
    }

}