<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;

    /**
     * Class UserSubscriptionManager
     * @package CoffeeHouse\Managers
     */
    class UserSubscriptionManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * UserSubscriptionManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }
    }