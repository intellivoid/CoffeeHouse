<?php

    namespace ModularAPI\Exceptions;

    use Exception;
    use ModularAPI\Abstracts\ExceptionCodes;

    class MissingParameterException extends Exception
    {
        public $ParameterName;

        /**
         * MissingParameterException constructor.
         * @param string $parameter_name
         */
        public function __construct(string $parameter_name)
        {
            $this->ParameterName = $parameter_name;
            parent::__construct('A Required Parameter is missing', ExceptionCodes::MissingParameterException, null);
        }
    }