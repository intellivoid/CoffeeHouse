<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class LanguagePredictionCacheManager
     * @package CoffeeHouse\Managers
     */
    class LanguagePredictionCacheManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * LanguagePredictionCacheManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }