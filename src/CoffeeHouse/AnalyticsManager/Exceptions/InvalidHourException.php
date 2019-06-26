<?php

    namespace AnalyticsManager\Exceptions;

    use AnalyticsManager\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class InvalidHourException
     * @package AnalyticsManager\Exceptions
     */
    class InvalidHourException extends Exception
    {
        /**
         * InvalidHourException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given hour is invalid for this object', ExceptionCodes::InvalidHourException, null);
        }
    }