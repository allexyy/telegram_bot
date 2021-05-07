<?php

namespace App\MessageHandler;

use App\Message\ChatMessages;
use App\Telegram\Service\TelegramRequests;
use App\Telegram\ValueObject\ChatMessage;
use App\Telegram\ValueObject\MessageData;
use App\Telegram\ValueObject\PhotoData;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class ChatMessagesHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    private TelegramRequests $requests;

    use LoggerAwareTrait;

    public function __construct(TelegramRequests $requests)
    {
        $this->requests  = $requests;
    }

    public function __invoke(ChatMessages $message)
    {
        $data = json_decode($message->getContent(), true);
        //TODO: Использовать стратегии
        if (isset($data['callback_query'])){
            $chatId = $data['callback_query']['message']['chat']['id'];
            $text = $data['callback_query']['message']['text'];
            $action = $data['callback_query']['data'];
            $chatMessage = new ChatMessage(
                $chatId,
                $text,
                $action);
        }
        elseif (isset($data['my_chat_member'])){
            $chatMessage = new ChatMessage(
                $data['my_chat_member']['chat']['id'],
                'Sorry something went wrong');
            return $this->requests->sendMessage(
                MessageData::getData($chatMessage,'Прости, но я не знаю как на это ответить'));
        }
        else{
            $chatId = $data['message']['chat']['id'];
            $text = $data['message']['text'];
            $chatMessage = new ChatMessage($chatId, $text);
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
