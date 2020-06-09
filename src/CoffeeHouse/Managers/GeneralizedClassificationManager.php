<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class GeneralizedClassificationManager
     * @package CoffeeHouse\Managers
     */
    class GeneralizedClassificationManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * GeneralizedClassificationManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }