<?php

    use CoffeeHouse\Bots\Cleverbot;
use CoffeeHouse\CoffeeHouse;
use CoffeeHouse\Exceptions\BotSessionException;
use ppm\ppm;

//$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */
    require("ppm");
    ppm::import("net.intellivoid.coffeehouse");

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        print("\n");
        return $line;
    }

    $CoffeeHouse = new CoffeeHouse();
    $Bot = new Cleverbot($CoffeeHouse);
    $Bot->newSession('en');

    while(true)
    {
        // Don't use local sessions for this example.
        $Output = $Bot->think(getInput(), true);
        print("Bot: $Output\n");
        print("Emotion: " . $Bot->getLocalSession()->AiCurrentEmotion . "\n");
        print("Language: " . $Bot->getLocalSession()->PredictedLanguage . "\n");
    }