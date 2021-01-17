<?php

use CoffeeHouse\CoffeeHouse;
use CoffeeHouse\Exceptions\ServerInterfaceException;

require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        $results = $CoffeeHouse->getCoreNLP()->sentiment(
            "My name is Lydia and I'm powered by CoffeeHouse, i was created in 2017 by Zi Xing Narrakas. There are plenty of examples where Lydia can respond like a real human, but there are plenty of issues that Lydia can struggle with when dealing with the human language."
        );

        var_dump($results);
    }
    catch(ServerInterfaceException $e)
    {
        var_dump($e);
    }