<?php

    //$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */

use CoffeeHouse\CoffeeHouse;
use ppm\ppm;

require("ppm");
    ppm::import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        print("\n");
        return $line;
    }

    $GeneralizedID = null;

    while(true)
    {
        if($GeneralizedID == null)
        {
            $Results = $CoffeeHouse->getSpamPrediction()->predict(getInput(), true);
        }
        else
        {
            $Results = $CoffeeHouse->getSpamPrediction()->predict(getInput(), true, $GeneralizedID);
        }

        var_dump($Results);
        if($Results->isSpam())
        {
            print("This is spam!" . PHP_EOL);
        }
        else
        {
            print("This isn't spam" . PHP_EOL);
        }

        if($Results->isGeneralizedSpam())
        {
            print("This is generally spam!" . PHP_EOL);
        }

        $GeneralizedID = $Results->GeneralizedID;
        print(PHP_EOL);
    }
