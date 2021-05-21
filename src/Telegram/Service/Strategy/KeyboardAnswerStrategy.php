<?php

declare(strict_types=1);

namespace App\Telegram\Service\Strategy;

use App\Telegram\ValueObject\ChatMessage;

class KeyboardAnswerStrategy implements AnswerStrategyInterface
{

    public function toChatMessage(array $messageData): ChatMessage
    {
        $chatId = (string)$messageData['callback_query']['message']['chat']['id'];
        $text = $messageData['callback_query']['message']['text'];
        $action = $messageData['callback_query']['data'];

        return new ChatMessage($chatId, $text, $action);

    }

    public function supports(array $messageData): bool
    {
        if (isset($messageData['callback_query'])){
            return true;
        }
        return false;
    }
}
