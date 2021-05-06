<?php


namespace App\Telegram\ValueObject;


class MessageData
{
    public static function getData(ChatMessage $message, string $text = 'Привет, я бот помощник' ,$keyboard = false)
    : array {
        $data = [
            'chat_id' => $message->getChatId(),
            'text' => $text, ];
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
                    [
                        'text' => "Как снять мерки ?",
                        'callback_data' => '{"action":"measurements","count":0,"text":"measurements"}',
                    ]

                ]]];
        }
        return $data;
    }

}