<?php

    namespace AnalyticsManager\Exceptions;

    use AnalyticsManager\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class InvalidDayException
     * @package AnalyticsManager\Exceptions
     */
    class InvalidDayException extends Exception
    {
        /**
         * InvalidDayException constructor.
         */
        public function __construct()
        {
            parent::__construct('The requested day does not exist in this object', ExceptionCodes::InvalidDayException, null);
        }
    }