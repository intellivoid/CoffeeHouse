<?php


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

    var_dump($CoffeeHouse->getEmotionPrediction()->predict("Hello there, my name is Zi Xing!"));
    var_dump($CoffeeHouse->getEmotionPrediction()->predict("I FUCKING HATE PYTHON, IT'S THE WORST FUCKING LANGAUGE IN THE WORLD"));