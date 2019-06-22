<?php

    use Longman\TelegramBot\Telegram;
    use Longman\TelegramBot\TelegramLog;

    require __DIR__ . '/vendor/autoload.php';
    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src';
    include_once($SourceDirectory . DIRECTORY_SEPARATOR . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    $API = '869979136:AAEi_uxDobRLwhC0wF0TMfkqAoy8IC0fA-0';
    $BotName = 'LydiaChatBot';


    $telegram = new Longman\TelegramBot\Telegram($API, $BotName);
    $commands_paths = [
        __DIR__ . DIRECTORY_SEPARATOR . 'commands',
    ];
    $telegram->addCommandsPaths($commands_paths);

    //Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/error.log");
    //Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/debug.log");
    //Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/update.log");

    $telegram->handle();