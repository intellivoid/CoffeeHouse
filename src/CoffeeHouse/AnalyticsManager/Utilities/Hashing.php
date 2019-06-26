<?php

    namespace AnalyticsManager\Utilities;

    /**
     * Class Hashing
     * @package AnalyticsManager\Utilities
     */
    class Hashing
    {
        /**
         * Peppers a hash using whirlpool
         *
         * @param string $Data The hash to pepper
         * @param int $Min Minimal amounts of executions
         * @param int $Max Maximum amount of executions
         * @return string
         */
        public static function pepper(string $Data, int $Min = 100, int $Max = 1000): string
        {
            $n = rand($Min, $Max);
            $res = '';
            $Data = hash('whirlpool', $Data);
            for ($i=0,$l=strlen($Data) ; $l ; $l--)
            {
                $i = ($i+$n-1) % $l;
                $res = $res . $Data[$i];
                $Data = ($i ? substr($Data, 0, $i) : '') . ($i < $l-1 ? substr($Data, $i+1) : '');
            }
            return($res);
        }

        /**
         * Creates a unique Public ID for the record
         *
         * @param string $name
         * @param int $creation_timestamp
         * @return string
         */
        public static function recordPublicID(string $name, int $creation_timestamp)
        {
            $name = self::pepper($name);
            return hash('sha256', $name . $creation_timestamp);
        }
    }