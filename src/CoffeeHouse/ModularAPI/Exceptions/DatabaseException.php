<?php

    namespace ModularAPI\Exceptions;

    use ModularAPI\Abstracts\ExceptionCodes;
    use RuntimeException;

    /**
     * Class DatabaseException
     * @package ModularAPI\Exceptions
     */
    class DatabaseException extends RuntimeException
    {
        /**
         * @var string
         */
        public $ReturnError;

        /**
         * @var mixed
         */
        private $Query;

        /**
         * DatabaseException constructor.
         * @param string $ReturnError
         * @param string $Query
         */
        public function __construct(string $ReturnError, $Query = '')
        {
            $this->ReturnError = $ReturnError;
            $this->Query = $Query;
            parent::__construct($ReturnError, ExceptionCodes::DatabaseException, null);
        }

        /**
         * Return error for the database
         */
        public function ReturnError(): string
        {
            return($this->ReturnError);
        }

        /**
         * @return mixed
         */
        public function Query()
        {
            return($this->Query);
        }

    }