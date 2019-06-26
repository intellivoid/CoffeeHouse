<?php

    namespace ModularAPI\Exceptions;
    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class InvalidRequestQueryException
     * @package ModularAPI\Exceptions
     */
    class InvalidRequestQueryException extends Exception
    {
        /**
         * InvalidRequestQueryException constructor.
         */
        public function __construct()
        {
            parent::__construct('The request query is invalid', ExceptionCodes::InvalidRequestQueryException, null);
        }
    }