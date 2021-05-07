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
        if (null === $request->getContent() || $request->getContent() === ''){
            return new JsonResponse('Empty response',204);
        }
        $bus->dispatch(new ChatMessages($request->getContent()));
        return new JsonResponse('ok');
    }

}