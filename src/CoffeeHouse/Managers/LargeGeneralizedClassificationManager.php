<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class LargeGeneralizedClassificationManager
     * @package CoffeeHouse\Manager
     */
    class LargeGeneralizedClassificationManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * LargeGeneralizedClassificationManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }