<?php

    use acm\acm;
    use acm\Objects\Schema;

    if(defined("PPM") == false)
    {
        if(class_exists('acm\acm') == false)
        {
            include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
        }
    }

    $acm = new acm(__DIR__, 'CoffeeHouse');

    $DatabaseSchema = new Schema();
    $DatabaseSchema->setDefinition('Host', '127.0.0.1');
    $DatabaseSchema->setDefinition('Port', '3306');
    $DatabaseSchema->setDefinition('Username', 'admin');
    $DatabaseSchema->setDefinition('Password', 'admin');
    $DatabaseSchema->setDefinition('Name', 'coffeehouse');
    $acm->defineSchema('Database', $DatabaseSchema);

    $ServerSchema = new Schema();
    $ServerSchema->setDefinition('Host', '127.0.0.1');
    $ServerSchema->setDefinition('PingPort', '5600');
    $ServerSchema->setDefinition('SpamPredictionPort', '5601');
    $ServerSchema->setDefinition('NsfwPredictionPort', '5602');
    $ServerSchema->setDefinition('TranslatePort', '5603');
    $ServerSchema->setDefinition('CoreNlpPort', '5604');
    $ServerSchema->setDefinition('EmotionsPort', '5605');
    $ServerSchema->setDefinition('LanguageDetectionPort', '5606');
    $acm->defineSchema('CoffeeHouseUtils', $ServerSchema);


    $ServerSchema = new Schema();
    $ServerSchema->setDefinition('TemporaryDirectory', '/tmp/coffeehouse');
    $acm->defineSchema('CoffeeHouse', $ServerSchema);

    $acm->processCommandLine();