<?php

    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    $input = "Intellivoid has now came to acquire Haruka Aya with the intention to improve it provide it a home on our servers, we trust that we will do a good job in keeping the integrity of Haruka Aya all while improving it to be the best competing Group Manager Bot that Telegram has ever seen. This can only be accomplished with hard work, in the next few days we will begin the process of moving Haruka Aya into Intellivoid's GitHub organization and start to focus on improving the bots performance, community and stability.";

    $Results = $CoffeeHouse->getTranslator()->translate($input, "español",
        $CoffeeHouse->getLanguagePrediction()->predict($input)->combineResults()[0]->Language
    );
    var_dump($Results);