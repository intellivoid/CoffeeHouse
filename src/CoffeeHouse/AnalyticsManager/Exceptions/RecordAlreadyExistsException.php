<?php

    namespace AnalyticsManager\Exceptions;

    use AnalyticsManager\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class RecordAlreadyExistsException
     * @package AnalyticsManager\Exceptions
     */
    class RecordAlreadyExistsException extends Exception
    {
        /**
         * RecordAlreadyExistsException constructor.
         */
        public function __construct()
        {
            parent::__construct('The record cannot be created because it already exists', ExceptionCodes::RecordAlreadyExistsException, null);
        }
    }