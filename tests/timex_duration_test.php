<?php

use CoffeeHouse\Objects\ProcessedNLP\Types\Duration;

require("ppm"); // Import the compiler!
    ppm_import("net.intellivoid.coffeehouse"); // Import the library!


    $data = [
        "PT3H" => "three hours",
        "PT20M" => "twenty minutes",
        "PT5S" => "5 seconds",
        "P512D" => "five hundred and twelve days",
        "PXY" => "a few decades",
        "P1000Y" => "hundred decades",
        "PXW" => "recent weeks"

    ];

    foreach($data as $key => $value)
    {
        print("Testing $key ($value)" . PHP_EOL);
        var_dump(Duration::fromSyntax($key));
        print(PHP_EOL);
    }