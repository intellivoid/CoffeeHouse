<?php
    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->invoke(
            "Hello there Bob, this a sentence. This is another sentence that you can use to process your stuff too!",
            ["ssplit"]
        ));
    }
    catch(\CoffeeHouse\Exceptions\ServerInterfaceException $e)
    {
        var_dump($e);
    }