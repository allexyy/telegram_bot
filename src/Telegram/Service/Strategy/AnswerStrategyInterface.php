<?php

declare(strict_types=1);

namespace App\Telegram\Service\Strategy;

use App\Telegram\ValueObject\ChatMessage;

interface AnswerStrategyInterface
{

    public function supports(array $messageData): bool;

    /**
     *
     * @param array $messageData Контент сообщения из чата
     * @return ChatMessage Сформированные данные сообщения из чата
     */
    public function toChatMessage(array $messageData): ChatMessage;

}
