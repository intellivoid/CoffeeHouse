<?php
    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        $results = $CoffeeHouse->getCoreNLP()->ner(
            "My name is Lydia and I'm powered by CoffeeHouse, i was created in 2017 by Zi Xing Narrakas"
        );

        var_dump($results);
    }
    catch(\CoffeeHouse\Exceptions\ServerInterfaceException $e)
    {
        var_dump($e);
    }