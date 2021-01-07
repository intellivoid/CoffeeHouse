<?php


    namespace CoffeeHouse\Abstracts;

    /**
     * Class TranslateProcessingEngine
     * @package CoffeeHouse\Abstracts
     */
    abstract class TranslateProcessingEngine
    {
        /**
         * The CoffeeHouse Translate services from CoffeeHousePy
         */
        const CoffeeHouseTranslate = "COFFEEHOUSE_TRANSLATE";

        /**
         * Native Google Translate feature
         */
        const GoogleTranslate = "GOOGLE_TRANSLATE";
    }