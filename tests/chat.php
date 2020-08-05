<?php

    use CoffeeHouse\Bots\Cleverbot;
    use CoffeeHouse\Exceptions\BotSessionException;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    /** @noinspection PhpIncludeInspection */
    require("ppm");
    include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        print("\n");
        return $line;
    }

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();
    $Bot = new Cleverbot($CoffeeHouse);
    $Bot->newSession('en');

    while(true)
    {
        $Output = $Bot->think(getInput());
        print("Bot: $Output\n");
    }