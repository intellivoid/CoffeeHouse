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

    var_dump($CoffeeHouse->getEmotionPrediction()->predictSentences("Hello there, my name is Zi Xing!"));
    var_dump($CoffeeHouse->getEmotionPrediction()->predictSentences(
        "This is a larger input that may contain different sentences. If you have the ability to predict emotional " .
        "data from text, you should do it by sentence to get a more accurate reading, since DLTC suffers from bad accuracy with large input." .
        "So this example should do!"
    ));

    var_dump($CoffeeHouse->getEmotionPrediction()->predictSentences(
        "There are many problems with this program which I don't like, for example... ".
        "Sentimental analysis is never correct and when it is, the text doesn't make sense and it's not readable."
    ));