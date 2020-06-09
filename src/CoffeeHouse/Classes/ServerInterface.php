<?php


    namespace CoffeeHouse\Classes;


    class ServerInterface
    {
        /**
         * @param string $module
         * @param string $path
         * @param array $parameters
         * @return string
         */
        public function sendRequest(string $module, string $path, array $parameters): string
        {

        }
    }