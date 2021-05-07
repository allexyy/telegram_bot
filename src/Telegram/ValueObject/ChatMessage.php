<?php

declare(strict_types=1);

namespace App\Telegram\ValueObject;

class ChatMessage
{
    private string $chatId;
    private string $text;
    private ?array $data = null;

    public function __construct(string $chatId, string $text, ?string $data = null)
    {
        $this->chatId = $chatId;
        $this->text   = $text;
        if ($data !== null){
            $this->data   = $this->setDataHelper($data);
        }
    }

    /**
     * @param string $data
     */
    public function setDataHelper(string $data): array
    {
       return $this->data = json_decode($data, true);
    }

    public function getAction()
    {
        return $this->data['action'] ?? null;
    }

    /**
     * @return string id чата в Телеграм
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string Текст сообщения
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function get()
    {
        
    }


}
