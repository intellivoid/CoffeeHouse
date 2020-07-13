<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class LanguagePrediction
     * @package CoffeeHouse\NaturalLanguageProcessing
     */
    class LanguagePrediction
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * LanguagePrediction constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }