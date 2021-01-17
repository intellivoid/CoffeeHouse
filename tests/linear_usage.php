<?php

    use CoffeeHouse\Classes\StringDistance;
use ppm\ppm;

//$SourceDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    //include_once($SourceDirectory . 'CoffeeHouse' . DIRECTORY_SEPARATOR . 'CoffeeHouse.php');

    /** @noinspection PhpIncludeInspection */
    require("ppm");
    ppm::import("net.intellivoid.coffeehouse");

    print("String 1: 'Hello World'\n");
    print("String 2: 'Hello World'\n");
    var_dump(StringDistance::levenshtein("Hello World", "Hello World"));
    print("\n\n");

    print("String 1: 'Hello World'\n");
    print("String 2: 'hello world'\n");
    var_dump(StringDistance::levenshtein("Hello World", "hello world"));
    print("\n\n");

    print("String 1: 'Hello World'\n");
    print("String 2: 'hello wold'\n");
    var_dump(StringDistance::levenshtein("Hello World", "hello wold"));
    print("\n\n");