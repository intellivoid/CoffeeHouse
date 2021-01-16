<?php
    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->posTag(
            "CoffeeHouse is a machine learning & artificial intelligence cloud engine created by Intellivoid in the year 2018."
        ));
    }
    catch(\CoffeeHouse\Exceptions\ServerInterfaceException $e)
    {
        var_dump($e);
    }