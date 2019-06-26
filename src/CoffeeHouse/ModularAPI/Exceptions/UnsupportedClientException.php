<?php

    namespace ModularAPI\Exceptions;

    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class UnsupportedClientException
     * @package ModularAPI\Exceptions
     */
    class UnsupportedClientException extends Exception
    {
        /**
         * UnsupportedClientException constructor.
         */
        public function __construct()
        {
            parent::__construct('The method is only supported for Web Clients', ExceptionCodes::UnsupportedClientException, null);
        }
    }