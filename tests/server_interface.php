<?php

    //$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */
    require("ppm");
    \ppm\ppm::import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        $Results = $CoffeeHouse->getServerInterface()->sendRequest(
            \CoffeeHouse\Abstracts\ServerInterfaceModule::SpamPrediction, "/",
            array(
                "input" => "Hello There!"
            )
        );
    }
    catch(Exception $exception)
    {
        var_dump($exception);
        exit(255);
    }

    print($Results . PHP_EOL);
    exit(0);