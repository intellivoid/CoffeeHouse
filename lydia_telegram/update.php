<?php

    require __DIR__ . '/vendor/autoload.php';

    //try
    //{

    //}
    //catch (Longman\TelegramBot\Exception\TelegramException $e)
    //{
        // log telegram errors
        // echo $e->getMessage();
    //}

    $API = '869979136:AAEi_uxDobRLwhC0wF0TMfkqAoy8IC0fA-0';
    $BotName = 'LydiaChatBot';
    $telegram = new Longman\TelegramBot\Telegram($API, $BotName);
    $result = $telegram->setWebhook('https://51cc17f3.ngrok.io');