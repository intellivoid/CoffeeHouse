<?php

use CoffeeHouse\CoffeeHouse;
use CoffeeHouse\Exceptions\ServerInterfaceException;

require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->sentenceSplit(
            "For every sentence that is split, we must understand why. For example, in many languages a sentence can end with a period."
        ));
    }
    catch(ServerInterfaceException $e)
    {
        var_dump($e);
    }