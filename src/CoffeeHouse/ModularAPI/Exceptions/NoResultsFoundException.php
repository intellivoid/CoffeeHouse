<?php

    namespace ModularAPI\Exceptions;
    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class NoResultsFoundException
     * @package ModularAPI\Exceptions
     */
    class NoResultsFoundException extends Exception
    {
        /**
         * NoResultsFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('No results were returned from the database', ExceptionCodes::NoResultsFoundException, null);
        }
    }