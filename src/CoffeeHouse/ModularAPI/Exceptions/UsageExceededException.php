<?php

    namespace ModularAPI\Exceptions;

    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class UsageExceededException
     * @package ModularAPI\Exceptions
     */
    class UsageExceededException extends Exception
    {
        /**
         * UsageExceededException constructor.
         */
        public function __construct()
        {
            parent::__construct('The usage limit has exceeded', ExceptionCodes::UsageExceededException, null);
        }
    }