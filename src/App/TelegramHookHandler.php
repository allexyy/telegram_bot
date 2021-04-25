<?php

declare(strict_types=1);

namespace App\App;


use App\Message\ChatMessages;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class TelegramHookHandler extends AbstractController
{
    /**
     * @Route ("/hook")
     */
    public function index(Request $request, MessageBusInterface $bus)
    {
//        file_put_contents('log_1.log', $request->getContent());
        $bus->dispatch(new ChatMessages($request->getContent()));
        return new JsonResponse('ok');
    }

}