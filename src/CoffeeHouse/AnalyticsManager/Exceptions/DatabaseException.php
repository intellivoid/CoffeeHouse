<?php

    namespace AnalyticsManager\Exceptions;

    use AnalyticsManager\Abstracts\ExceptionCodes;
    use Exception;


    /**
     * Class DatabaseException
     * @package AnalyticsManager\Exceptions
     */
    class DatabaseException extends Exception
    {
        /**
         * @var string
         */
        private $error;

        /**
         * @var string
         */
        private $query;

        /**
         * DatabaseException constructor.
         * @param string $error
         * @param string $query
         */
        public function __construct(string $error, string $query)
        {
            parent::__construct('There was a database exception', ExceptionCodes::DatabaseException, null);
            $this->error = $error;
            $this->query = $query;
        }

        /**
         * @return string
         */
        public function getError(): string
        {
            return $this->error;
        }

        /**
         * @return string
         */
        public function getQuery(): string
        {
            return $this->query;
        }

    }