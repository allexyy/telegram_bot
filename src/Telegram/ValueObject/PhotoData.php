<?php


namespace App\Telegram\ValueObject;


class PhotoData
{
    public static function getData(ChatMessage $message, $keyboard = false): array
    {
        $data = [
            'chat_id' => $message->getChatId(),
            'photo'   => 'https://1fb9414fa409.ngrok.io/images/measurement.jpeg' ];
        if ($keyboard){
            $data['reply_markup'] = [
                'inline_keyboard' =>[ [
                    [
                        'text' => "Сделать заказ",
                        'callback_data' => '{"action":"order","count":0,"text":"order"}',
                    ],
                    [
                        'text' => "Примеры работ",
                        'callback_data' => '{"action":"show","count":0,"text":"show"}',
                    ],
                ]]];
        }
        return $data;
    }

}