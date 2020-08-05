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

    $GeneralizedPublicID = null;
    $Limit = 100;

    while(true)
    {
        $Results = $CoffeeHouse->getLanguagePrediction()->predict(getInput(), false, true, true);
        $Generalized = $CoffeeHouse->getLanguagePrediction()->generalize($Results, $GeneralizedPublicID, $Limit, false);
        $GeneralizedPublicID = $Generalized->PublicID;

        //var_dump($Generalized->combineProbabilities());
        //var_dump($Generalized->combineProbabilities()[0]);
        print("Language: " . $Generalized->TopLabel . "(" . $Generalized->TopProbability . ")" . PHP_EOL);
        //print("Public ID: " . $Generalized->TopLabel . "(" . $Generalized->PublicID . ")" . PHP_EOL);
    }
