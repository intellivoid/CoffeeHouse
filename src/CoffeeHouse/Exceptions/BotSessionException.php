<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class BotSessionException
     * @package CoffeeHouse\Exceptions
     */
    class BotSessionException extends Exception
    {
        /**
         * @var mixed
         */
        private $error_details;

        /**
         * BotSessionException constructor.
         * @param $error_details
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct($error_details)
        {
            $this->error_details = $error_details;
            parent::__construct("There was an error with the bot session", ExceptionCodes::BotSessionException);
        }

        /**
         * @return mixed
         */
        public function getErrorDetails()
        {
            return $this->error_details;
        }


    }