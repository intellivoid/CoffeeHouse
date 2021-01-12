<?php
    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->sentenceSplit(
            "For every sentence that is split, we must understand why. For example, in many languages a sentence can end with a period."
        ));
    }
    catch(\CoffeeHouse\Exceptions\ServerInterfaceException $e)
    {
        var_dump($e);
    }