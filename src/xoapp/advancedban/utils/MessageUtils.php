<?php

namespace xoapp\advancedban\utils;

use xoapp\advancedban\data\MessageData;

class MessageUtils
{

    public static function getMessage(string $id, string $key, array $params = []): string
    {
        $message = MessageData::getInstance()->getData($id);
        return strtr($message[$key], $params);
    }
}