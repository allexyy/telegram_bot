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
use TelegramBot\Api\HttpException;
use Throwable;

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
        } else{
            try {
                $chatId = $data['message']['chat']['id'];
                $text = $data['message']['text'];
                $chatMessage = new ChatMessage($chatId, $text);
            } catch (Throwable $exception){
                $this->logger->error($exception->getMessage());
                throw new HttpException($exception->getMessage());
            }
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
            return $this->requests->sendPhoto(PhotoData::getData($chatMessage));
        }
        if ($chatMessage->getAction() === "order"){
            return $this->requests->sendMessage(
                MessageData::getData($chatMessage, 'Отлично !!! Пришли пожалуйста замеры'));
        }

        return $this->requests->sendMessage(
            MessageData::getData($chatMessage,'Прости, но я не знаю как на это ответить'));
    }
}
