<?php

    namespace ModularAPI\Exceptions;
    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class AccessKeyNotFoundException
     * @package ModularAPI\Exceptions
     */
    class AccessKeyNotFoundException extends Exception
    {
        /**
         * AccessKeyNotFoundException constructor.
         */
        public function __construct()
        {
            parent::__construct('The Access Key was not found in the database', ExceptionCodes::AccessKeyNotFoundException, null);
        }
    }