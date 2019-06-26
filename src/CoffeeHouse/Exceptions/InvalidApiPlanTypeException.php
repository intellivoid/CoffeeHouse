<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class InvalidApiPlanTypeException
     * @package CoffeeHouse\Exceptions
     */
    class InvalidApiPlanTypeException extends Exception
    {
        /**
         * InvalidApiPlanTypeException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given API Plan Type is invalid', ExceptionCodes::InvalidApiPlanTypeException, null);
        }
    }