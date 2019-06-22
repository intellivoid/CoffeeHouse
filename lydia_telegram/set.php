<?php


    // Load composer
    require __DIR__ . '/vendor/autoload.php';

    $bot_api_key = '869979136:AAEi_uxDobRLwhC0wF0TMfkqAoy8IC0fA-0';
    $bot_username = 'LydiaChatBot';
    $hook_url = 'https://your-domain/path/to/hook.php';

    try {
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

        // Set webhook
        $result = $telegram->setWebhook($hook_url);
        if ($result->isOk()) {
            echo $result->getDescription();
        }
    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        // echo $e->getMessage();
    }