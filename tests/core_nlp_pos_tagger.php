<?php

use CoffeeHouse\CoffeeHouse;
use CoffeeHouse\Exceptions\ServerInterfaceException;

require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->posTag(
            "CoffeeHouse is a machine learning & artificial intelligence cloud engine created by Intellivoid in the year 2018."
        ));
    }
    catch(ServerInterfaceException $e)
    {
        var_dump($e);
    }