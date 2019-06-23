<?php

    use acm\acm;
    use acm\Objects\Schema;

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    $acm = new acm(__DIR__, 'CoffeeHouse');

    $DatabaseSchema = new Schema();
    $DatabaseSchema->setDefinition('Host', 'localhost');
    $DatabaseSchema->setDefinition('Port', '3306');
    $DatabaseSchema->setDefinition('Username', 'admin');
    $DatabaseSchema->setDefinition('Password', 'admin');
    $DatabaseSchema->setDefinition('Name', 'coffee_house');
    $acm->defineSchema('Database', $DatabaseSchema);

    $TelegramSchema = new Schema();
    $TelegramSchema->setDefinition('BotName', 'LydiaChatBot');
    $TelegramSchema->setDefinition('ApiKey', '<API KEY>');
    $TelegramSchema->setDefinition('WebHook', 'https://localhost/');
    $acm->defineSchema('Telegram', $TelegramSchema);

    $ModularApiSchema = new Schema();
    $ModularApiSchema->setDefinition('IssuerName', 'Intellivoid');
    $acm->defineSchema('ModularAPI', $ModularApiSchema);

    $acm->processCommandLine();