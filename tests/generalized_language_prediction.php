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

    $LargeGeneralization = $CoffeeHouse->getLargeGeneralizedClassificationManager()->create(30);
    var_dump($LargeGeneralization);

    $GeneralizedPublicID = $LargeGeneralization->PublicID;
    $Limit = 100;

    $AutomatedSentences = [
        "I ate dinner.",
        "We had a three-course meal.",
        "Brad came to dinner with us.",
        "He loves fish tacos.",
        "In the end, we all felt like we ate too much.",
        "We all agreed; it was a magnificent evening.",
        "I hope that, when I've built up my savings, I'll be able to travel to Mexico.",
        "Did you know that, along with gorgeous architecture, it's home to the largest tamale?",
        "Wouldn't it be lovely to enjoy a week soaking up the culture?",
        "Oh, how I'd love to go!",
        "Of all the places to travel, Mexico is at the top of my list.",
        "Would you like to travel with me?",
        "Isn't language learning fun?",
        "There is so much to understand.",
        "I love learning!",
        "Sentences come in many shapes and sizes.",
        "Nothing beats a complete sentence.",
        "Once you know all the elements, it's not difficult to pull together a sentence."
    ];

    while(true)
    {
        foreach($AutomatedSentences as $sentence)
        {
            print(" > " . $sentence . PHP_EOL);
            $Results = $CoffeeHouse->getLanguagePrediction()->predict($sentence, false, true, true, true);
            $LargeGeneralization = $CoffeeHouse->getLanguagePrediction()->generalize($LargeGeneralization, $Results);

            //var_dump($Generalized->combineProbabilities());
            //var_dump($Generalized->combineProbabilities()[0]);
            print("Language: " . $LargeGeneralization->TopLabel . "(" . $LargeGeneralization->TopProbability . ")" . PHP_EOL);
            //print("Public ID: " . $Generalized->TopLabel . "(" . $Generalized->PublicID . ")" . PHP_EOL);
        }

    }
