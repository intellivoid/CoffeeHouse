<?php


    namespace CoffeeHouse\Classes;


    /**
     * Class Hashing
     * @package CoffeeHouse\Classes
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
         * Creates icognocheck Code
         *
         * @param string $vars
         * @return string
         */
        public static function icognocheckCode(string $vars): string
        {
            $data = substr($vars . '&icognocheck=', 7, 26);
            return(hash('md5', $data));
        }

        /**
         * Creates a foreign session id
         *
         * @param string $vars
         * @param string $language
         * @param int $time
         * @return string
         */
        public static function foreignSessionId(string $language, int $time)
        {
            $vars_c = hash('sha256', self::pepper($time) . $language);
            return hash('sha256', $vars_c . $time);
        }
    }