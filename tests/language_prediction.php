<?php

    //$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */
    require("ppm");
    \ppm\ppm::import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    function getInput(): string
    {
        print(" > ");
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        print("\n");
        return $line;
    }

    while(true)
    {
        $Results = $CoffeeHouse->getLanguagePrediction()->predict(getInput(), false, true, true);
        print("Language: " . $Results->combineResults()[0]->Language . "(" . $Results->combineResults()[0]->Probability . ")");
        print(PHP_EOL);
    }
