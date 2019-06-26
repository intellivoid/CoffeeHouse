<?php

    namespace ModularAPI\Utilities;

    /**
     * Class Checker
     * @package ModularAPI\Utilities
     */
    class Checker
    {
        /**
         * Determines if it's a web request
         *
         * @return bool
         */
        public static function isWebRequest(): bool
        {
            if(http_response_code() !== false)
            {
                return true;
            }

            return false;
        }

        /**
         * Determines if the data is base64 valid
         *
         * @param $data
         * @return bool
         */
        public static function isBase64($data): bool
        {
            return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $data);
        }
    }