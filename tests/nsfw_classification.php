<?php


    use CoffeeHouse\CoffeeHouse;

    require("ppm");
    ppm_import("net.intellivoid.coffeehouse");

    $CoffeeHouse = new CoffeeHouse();

    print("nsfw.jpeg results" . PHP_EOL);
    var_dump($CoffeeHouse->getNsfwClassification()->classifyImageFile(
        __DIR__ . DIRECTORY_SEPARATOR . "test_images" . DIRECTORY_SEPARATOR . "nsfw.jpeg"
    ));

    print("sfw.png results" . PHP_EOL);
    var_dump($CoffeeHouse->getNsfwClassification()->classifyImageFile(
        __DIR__ . DIRECTORY_SEPARATOR . "test_images" . DIRECTORY_SEPARATOR . "sfw.png"
    ));