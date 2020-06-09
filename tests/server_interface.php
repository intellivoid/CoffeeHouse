<?php

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    $Results = $CoffeeHouse->getServerInterface()->sendRequest(
        \CoffeeHouse\Abstracts\ServerInterfaceModule::SpamDetection, "/",
        array(
            "input" => "Hello There!"
        )
    );

    print($Results . PHP_EOL);