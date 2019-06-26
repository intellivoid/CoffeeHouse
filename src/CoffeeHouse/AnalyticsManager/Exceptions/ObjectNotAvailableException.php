<?php

    namespace AnalyticsManager\Exceptions;

    use AnalyticsManager\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class ObjectNotAvailableException
     * @package AnalyticsManager\Exceptions
     */
    class ObjectNotAvailableException extends Exception
    {
        /**
         * ObjectNotAvailableException constructor.
         */
        public function __construct()
        {
            parent::__construct('The object is not available', ExceptionCodes::ObjectNotAvailableException, null);
        }
    }