<?php


    namespace CoffeeHouse\Exceptions;

    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class ApiPlanNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class ApiPlanNotFoundException extends Exception
    {
        /**
         * ApiPlanNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('The API Plan was not found in the database', ExceptionCodes::ApiPlanNotFoundException, null);
        }
    }