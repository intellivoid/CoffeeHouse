<?php

    //$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */

use CoffeeHouse\Abstracts\ServerInterfaceModule;
use CoffeeHouse\CoffeeHouse;
use ppm\ppm;

require("ppm");
    ppm::import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        $Results = $CoffeeHouse->getServerInterface()->sendRequest(
            ServerInterfaceModule::SpamPrediction, "/",
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