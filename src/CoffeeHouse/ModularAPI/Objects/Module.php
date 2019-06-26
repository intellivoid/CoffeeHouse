<?php

    namespace ModularAPI\Objects;

    /**
     * Class Module
     * @package ModularAPI\Objects
     */
    class Module
    {
        /**
         * The name of the module
         *
         * @var string
         */
        public $Name;

        /**
         * Indicates if this module requires authentication to utilize
         *
         * @var bool
         */
        public $RequireAuthentication;

        /**
         * Indicates if this module requires usage resources
         *
         * @var bool
         */
        public $RequireUsage;

        /**
         * Indicates if the POST Method is allowed within the request
         *
         * @var bool
         */
        public $PostMethodAllowed;

        /**
         * Indicates if the GET Method is allowed within the request
         *
         * @var bool
         */
        public $GetMethodAllowed;

        /**
         * The name of the script that gets executed for this module (without .php extension)
         *
         * @var string
         */
        public $ScriptName;

        /**
         * List of expected parameters
         *
         * @var array
         */
        public $Parameters;

        /**
         * Converts the object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'REQUIRE_AUTHENTICATION' => (bool)$this->RequireAuthentication,
                'REQUIRE_USAGE' => (bool)$this->RequireUsage,
                'POST_METHOD_ALLOWED' => (bool)$this->PostMethodAllowed,
                'GET_METHOD_ALLOWED' => (bool)$this->GetMethodAllowed,
                'SCRIPT' => (string)$this->ScriptName,
                'PARAMETERS' => $this->Parameters
            );
        }

        /**
         * Creates object from array
         *
         * @param string $name
         * @param array $data
         * @return Module
         */
        public static function fromArray(string $name, array $data): Module
        {
            $ModuleObject = new Module();

            $ModuleObject->Name = $name;

            if(isset($data['REQUIRE_AUTHENTICATION']))
            {
                $ModuleObject->RequireAuthentication = (bool)$data['REQUIRE_AUTHENTICATION'];
            }
            else
            {
                $ModuleObject->RequireAuthentication = false;
            }

            if(isset($data['REQUIRE_USAGE']))
            {
                $ModuleObject->RequireUsage = (bool)$data['REQUIRE_USAGE'];
            }
            else
            {
                $ModuleObject->RequireUsage = false;
            }

            if(isset($data['POST_METHOD_ALLOWED']))
            {
                $ModuleObject->PostMethodAllowed = (bool)$data['POST_METHOD_ALLOWED'];
            }
            else
            {
                $ModuleObject->PostMethodAllowed = true;
            }

            if(isset($data['GET_METHOD_ALLOWED']))
            {
                $ModuleObject->GetMethodAllowed = (bool)$data['GET_METHOD_ALLOWED'];
            }
            else
            {
                $ModuleObject->GetMethodAllowed = true;
            }

            if(isset($data['SCRIPT']))
            {
                $ModuleObject->ScriptName = (string)$data['SCRIPT'];
            }
            else
            {
                $ModuleObject->ScriptName = null;
            }

            if(isset($data['PARAMETERS']))
            {
                $ModuleObject->Parameters = $data['PARAMETERS'];
            }
            else
            {
                $ModuleObject->Parameters = array();
            }

            return $ModuleObject;
        }
    }