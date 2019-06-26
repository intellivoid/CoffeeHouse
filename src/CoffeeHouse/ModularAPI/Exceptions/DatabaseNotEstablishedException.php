<?php

    namespace ModularAPI\Exceptions;

    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class DatabaseNotEstablishedException
     * @package ModularAPI\Exceptions
     */
    class DatabaseNotEstablishedException extends Exception
    {
        /**
         * DatabaseNotEstablishedException constructor.
         */
        public function __construct()
        {
            parent::__construct('The database connection has not been established', ExceptionCodes::DatabaseNotEstablishedException, null);
        }
    }