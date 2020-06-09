<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Objects\Results\SpamPredictionResults;

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

        public function predict(string $input, bool $cache=false): SpamPredictionResults
        {
            $SpamPredictionCache = null;

            if($cache)
            {
                $SpamPredictionCache = $this->coffeeHouse->
            }
        }
    }