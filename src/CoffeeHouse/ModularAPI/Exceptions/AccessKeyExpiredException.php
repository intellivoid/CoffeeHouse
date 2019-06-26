<?php

    namespace ModularAPI\Exceptions;

    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    /**
     * Class AccessKeyExpiredException
     * @package ModularAPI\Exceptions
     */
    class AccessKeyExpiredException extends Exception
    {
        /**
         * AccessKeyExpiredException constructor.
         */
        public function __construct()
        {
            parent::__construct('The access key has expired', ExceptionCodes::AccessKeyExpiredException, null);
        }
    }