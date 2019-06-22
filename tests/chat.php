<?php

    use CoffeeHouse\Bots\Cleverbot;

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        return $line;
    }

    $Bot = new Cleverbot();

    $Session = null;
    while(true)
    {
        $Bot->createSession('en', $Session);
        $Response = $Bot->think(getInput());
        $Session = $Response->getSession();
        print("Bot: " . $Response->getOutput() . "\n");
    }