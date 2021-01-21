<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class ForeignSessionNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class ForeignSessionNotFoundException extends Exception
    {
        /**
         * ForeignSessionNotFoundException constructor.
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct()
        {
            parent::__construct('The foreign session was not found in the database', ExceptionCodes::ForeignSessionNotFoundException);
        }
    }