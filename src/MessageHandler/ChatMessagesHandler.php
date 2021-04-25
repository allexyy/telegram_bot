<?php

namespace App\MessageHandler;

use App\Message\ChatMessages;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ChatMessagesHandler implements MessageHandlerInterface
{
    public function __invoke(ChatMessages $message)
    {
        file_put_contents('log.log',$message->getContent());
    }
}
