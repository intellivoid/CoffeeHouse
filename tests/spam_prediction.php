<?php

    $SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    /** @noinspection PhpIncludeInspection */
    require("ppm");
    include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

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
        $Results = $CoffeeHouse->getSpamPrediction()->predict(getInput());
        var_dump($Results);
        if($Results->isSpam())
        {
            print("This is spam!" . PHP_EOL);
        }
        else
        {
            print("This isn't spam" . PHP_EOL);
        }
        print(PHP_EOL);
    }
