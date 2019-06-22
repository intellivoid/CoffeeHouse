<?php


    namespace CoffeeHouse\Exceptions;

    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class TelegramClientNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class TelegramClientNotFoundException extends Exception
    {

        /**
         * TelegramClientNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('The Telegram Client was not found in the database', ExceptionCodes::TelegramClientNotFoundException, null);
        }
    }