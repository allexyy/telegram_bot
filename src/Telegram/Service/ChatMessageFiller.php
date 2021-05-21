<?php

declare(strict_types=1);

namespace App\Telegram\Service;

use App\Message\ChatMessages;
use App\Telegram\Service\Strategy\AnswerStrategyInterface;
use App\Telegram\Service\Strategy\CommandAnswerStrategy;
use App\Telegram\Service\Strategy\KeyboardAnswerStrategy;
use App\Telegram\ValueObject\ChatMessage;

class ChatMessageFiller
{

    /** @var AnswerStrategyInterface[] */
    private iterable $strategies;

    public function __construct(KeyboardAnswerStrategy $keyboard, CommandAnswerStrategy $command)
    {
        $this->strategies = [$keyboard, $command];
    }

    public function fill(ChatMessages $message): ?ChatMessage
    {
        $data = json_decode($message->getContent(), true);
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($data)){
                return $strategy->toChatMessage($data);
            }
        }
        return null;
    }
}
