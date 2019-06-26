<?php

    namespace ModularAPI\Exceptions;
    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class InvalidAccessKeyStatusException
     * @package ModularAPI\Exceptions
     */
    class InvalidAccessKeyStatusException extends Exception
    {
        /**
         * InvalidAccessKeyStatusException constructor.
         */
        public function __construct()
        {
            parent::__construct('The given Access Key Status is invalid', ExceptionCodes::InvalidAccessKeyStatusException, null);
        }
    }