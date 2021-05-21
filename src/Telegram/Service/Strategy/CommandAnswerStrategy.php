<?php

declare(strict_types=1);

namespace App\Telegram\Service\Strategy;

use App\Telegram\ValueObject\ChatMessage;

class CommandAnswerStrategy implements AnswerStrategyInterface
{

    public function toChatMessage(array $messageData): ChatMessage
    {
        $chatId = (string)$messageData['message']['chat']['id'];
        $text = $messageData['message']['text'];

        return new ChatMessage($chatId, $text);
    }

    public function supports(array $messageData): bool
    {
        if (isset($messageData['message'])){
            return true;
        }
        return false;
    }
}