<?php

declare(strict_types=1);

namespace App\Message;

final class ChatMessages
{
    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

     private string $content;

     public function __construct(string $content)
     {
         $this->content = $content;
     }

    public function getContent(): string
    {
        return $this->content;
    }
}
