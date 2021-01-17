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

    $LargeGeneralization = $CoffeeHouse->getLargeGeneralizedClassificationManager()->create(30);
    var_dump($LargeGeneralization);

    $GeneralizedPublicID = $LargeGeneralization->PublicID;


    while(true)
    {
        $Results = $CoffeeHouse->getEmotionPrediction()->predict(getInput(), "auto");
        $LargeGeneralization = $CoffeeHouse->getEmotionPrediction()->generalize($LargeGeneralization, $Results);

        print("Emotion: " . $LargeGeneralization->TopLabel . "(" . $LargeGeneralization->TopProbability . ")" . PHP_EOL);

    }