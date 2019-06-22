<?php

    use CoffeeHouse\Bots\Cleverbot;
    use CoffeeHouse\Exceptions\BotSessionException;

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

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();
    $Bot = new Cleverbot($CoffeeHouse);

    $Bot->loadSession('bb5cc4fd78e831f5112dcc8747a387615dc068d4967de943e81db3bcf231520e');
    echo $Bot->think("Hello");