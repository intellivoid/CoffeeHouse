<?php

    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\ServerInterfaceException;

    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    try
    {
        $results = $CoffeeHouse->getCoreNLP()->sentiment(
            "They use text book communist tactics of distracting and demoralization to break trust within populations, build a new connection to an overarching government that is then enslaved to the banking system with perpetuals; \"forever debt\" that is never to be paid. They distract us with sexual degeneracy and activism, break trust between people by creating political divide in the home and in the work place, weaken our ability of critical thinking by ways of silencing, addicting garbage food and electromagnetic radiation, establish socialism to make sure people feel supported by a government instead of each other, and then ensure government compliance to forever debt by punishing any country going against this plan. Just look at how hard the EU has been trying to punish Great Britain, Hungary, Poland and Russia for in any way not complying with their policies."
        );

        var_dump($results);
    }
    catch(ServerInterfaceException $e)
    {
        var_dump($e);
    }