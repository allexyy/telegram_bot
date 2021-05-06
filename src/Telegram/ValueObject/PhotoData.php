<?php


namespace App\Telegram\ValueObject;


class PhotoData
{
    public static function getData(ChatMessage $message): array
    {
        return [
            'chat_id' => $message->getChatId(),
            'photo'   => '../../../public/images/measurement.jpeg'
        ];
    }

}