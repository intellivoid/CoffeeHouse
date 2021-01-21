<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class MalformedDataException
     * @package CoffeeHouse\Exceptions
     */
    class MalformedDataException extends Exception
    {
        /**
         * MalformedDataException constructor.
         * @param string $message
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct($message = "")
        {
            parent::__construct($message, ExceptionCodes::MalformedDataException);
        }
    }