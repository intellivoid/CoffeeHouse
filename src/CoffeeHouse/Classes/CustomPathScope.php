<?php


    namespace CoffeeHouse\Classes;

    use CoffeeHouse\CoffeeHouse;

    /**
     * Class CustomPathScope
     * @package CoffeeHouse\Classes
     */
    class CustomPathScope
    {
        /**
         * Processes triggers for custom path scopes
         *
         * @param string $input
         * @return string|null
         */
        public static function processTriggers(string $input)
        {
            $CPS = CoffeeHouse::getCustomPathScopes();

            foreach($CPS['triggers'] as $Trigger => $Properties)
            {
                $Calculation = levenshtein($input, $Trigger);
                if($Calculation < $Properties['max_points'])
                {
                    return self::getOutput($Properties['output']);
                }
            }

            return null;
        }

        public static function getOutput(string $output): string
        {

        }
    }