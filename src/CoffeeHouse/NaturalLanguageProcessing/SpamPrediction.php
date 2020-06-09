<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class SpamPrediction
     * @package CoffeeHouse\NaturalLanguageProcessing
     */
    class SpamPrediction
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * SpamPrediction constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }