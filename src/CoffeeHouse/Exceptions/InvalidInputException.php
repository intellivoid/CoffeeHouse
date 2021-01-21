<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class InvalidInputException
     * @package CoffeeHouse\Exceptions
     */
    class InvalidInputException extends Exception
    {
        /**
         * InvalidInputException constructor.
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct()
        {
            parent::__construct("The given input cannot be empty", ExceptionCodes::InvalidInputException);
        }
    }