<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;

    /**
     * Class ServerInterface
     * @package CoffeeHouse\Classes
     */
    class ServerInterface
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeehouse;

        /**
         * ServerInterface constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeehouse = $coffeeHouse;
        }

        /**
         * @param string|ServerInterfaceModule $module
         * @param string $path
         * @param array $parameters
         * @return string
         */
        public function sendRequest(string $module, string $path, array $parameters): string
        {

        }

        public function resolveModule(string $module)
    }