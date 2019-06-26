<?php

    namespace ModularAPI\Exceptions;
    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class UnsupportedSearchMethodException
     * @package ModularAPI\Exceptions
     */
    class UnsupportedSearchMethodException extends Exception
    {
        /**
         * UnsupportedSearchMethodException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given search method is unsupported for this method', ExceptionCodes::UnsupportedSearchMethodException, null);
        }
    }