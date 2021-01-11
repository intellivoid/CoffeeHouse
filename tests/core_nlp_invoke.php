<?php
    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new \CoffeeHouse\CoffeeHouse();

    try
    {
        var_dump($CoffeeHouse->getCoreNLP()->processText("有很多事情可能出错，例如在1996年，出现了问题。 噢，这应该翻译成英文，因为像鲍勃这样的很多人都不懂中文。 太好了！", "auto"));
    }
    catch(\CoffeeHouse\Exceptions\ServerInterfaceException $e)
    {
        var_dump($e);
    }