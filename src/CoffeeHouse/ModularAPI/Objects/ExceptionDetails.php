<?php

    namespace ModularAPI\Objects;

    /**
     * Class ExceptionDetails
     * @package ModularAPI\Objects
     */
    class ExceptionDetails
    {
        /**
         * The Exception Code
         *
         * @var int
         */
        public $ExceptionCode;

        /**
         * The exception message
         *
         * @var string
         */
        public $Message;

        /**
         * The affected file
         *
         * @var string
         */
        public $File;

        /**
         * The affected line
         *
         * @var int
         */
        public $Line;

        /**
         * Converts the object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'exception_code' => $this->ExceptionCode,
                'message' => $this->Message,
                'file' => $this->File,
                'line' => $this->Line
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return ExceptionDetails
         */
        public static function fromArray(array $data): ExceptionDetails
        {
            $ExceptionDetailsObject = new ExceptionDetails();

            if(isset($data['exception_code']))
            {
                $ExceptionDetailsObject->ExceptionCode = (int)$data['exception_code'];
            }
            else
            {
                $ExceptionDetailsObject->ExceptionCode = 0;
            }

            if(isset($data['message']))
            {
                $ExceptionDetailsObject->Message = (string)$data['message'];
            }
            else
            {
                $ExceptionDetailsObject->Message = 'None';
            }

            if(isset($data['file']))
            {
                $ExceptionDetailsObject->File = (string)$data['file'];
            }
            else
            {
                $ExceptionDetailsObject->File = 'None';
            }

            if(isset($data['line']))
            {
                $ExceptionDetailsObject->Line = (int)$data['line'];
            }
            else
            {
                $ExceptionDetailsObject->Line = 0;
            }

            return $ExceptionDetailsObject;
        }
    }