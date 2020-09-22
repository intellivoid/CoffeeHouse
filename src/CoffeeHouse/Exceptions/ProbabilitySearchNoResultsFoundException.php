<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class ProbabilitySearchNoResultsFoundException
     * @package CoffeeHouse\Exceptions
     */
    class ProbabilitySearchNoResultsFoundException extends Exception
    {
        /**
         * ProbabilitySearchNoResultsFoundException constructor.
         * @param string $message
         */
        public function __construct($message = "")
        {
            parent::__construct($message, ExceptionCodes::ProbabilitySearchNoResultsFoundException, null);
        }
    }