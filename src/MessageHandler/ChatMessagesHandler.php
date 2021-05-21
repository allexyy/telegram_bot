<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ChatMessages;
use App\Telegram\Service\ChatMessageFiller;
use App\Telegram\Service\TelegramRequests;
use App\Telegram\ValueObject\MessageData;
use App\Telegram\ValueObject\PhotoData;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ChatMessagesHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    private TelegramRequests $requests;
    private ChatMessageFiller $filler;

    use LoggerAwareTrait;

    public function __construct(TelegramRequests $requests, ChatMessageFiller $filler)
    {
        $this->requests  = $requests;
        $this->filler    = $filler;
    }

    public function __invoke(ChatMessages $message)
    {
        $chatMessage = $this->filler->fill($message);

        if (!$chatMessage){
            return $this->requests->sendMessage(
                MessageData::getData($chatMessage,'HMMM..... OK'));
        }

        //TODO: Добавляем ветвление на (/start, показать еще, заказать, как мерить ?)
        if ($chatMessage->getText() === '/start'){
           return $this->requests->sendMessage(MessageData::getData($chatMessage,'Привет, я бот помощник',true));
        }
        if ($chatMessage->getAction() === "order"){
            return $this->requests->sendMessage(
                MessageData::getData($chatMessage, 'Отлично !!! Пришли пожалуйста замеры'));
        }
        if ($chatMessage->getAction() === "measurements"){
            $this->requests->sendMessage(
                MessageData::getData($chatMessage, 'Снять мерки очень просто....'));
            return $this->requests->sendPhoto(PhotoData::getData($chatMessage, true));
        }
        if ($chatMessage->getAction() === "show"){
            return $this->requests->sendMessage(
                MessageData::getData($chatMessage, 'Посмотреть примеры работ ты можешь в Instagram: https://www.instagram.com/si_li_lingerie'));
        }

        return $this->requests->sendMessage(
            MessageData::getData($chatMessage,'Прости, но я не знаю как на это ответить'));
    }
}
