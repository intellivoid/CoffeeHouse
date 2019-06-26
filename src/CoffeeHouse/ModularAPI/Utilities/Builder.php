<?php

    namespace ModularAPI\Utilities;

    /**
     * Class Builder
     * @package ModularAPI\Utilities
     */
    class Builder
    {
        /**
         * Builds an array of month
         *
         * @return array
         */
        public static function createMonthArray(): array
        {
            $dates = array();

            for($i = 1; $i <=  date('t'); $i++)
            {
                // add the date to the dates array
                $dates[] = 0;
            }

            return $dates;
        }
    }