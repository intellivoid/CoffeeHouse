<?php

    namespace ModularAPI\Objects;

    /**
     * Class Configuration
     * @package ModularAPI\Objects
     */
    class Configuration
    {
        /**
         * The API Version
         *
         * @var string
         */
        public $Version;

        /**
         * The API Configuration
         *
         * @var API
         */
        public $API;

        /**
         * Policies for the API
         *
         * @var Policies
         */
        public $Policies;

        /**
         * List of Modules that are configured for this API
         * These arrays can be converted to Module Object
         *
         * @var array
         */
        public $Modules;

        /**
         * Determines if the module exists
         *
         * @param string $module
         * @return bool
         */
        public function moduleExists(string $module): bool
        {
            foreach($this->Modules as $module_name => $module_data)
            {
                if(strtoupper($module_name) == strtoupper($module))
                {
                    return true;
                }
            }

            return false;
        }

        /**
         * Loads module data
         *
         * @param string $module
         * @return Module
         */
        public function getModule(string $module): Module
        {
            foreach($this->Modules as $module_name => $module_data)
            {
                if(strtoupper($module_name) == strtoupper($module))
                {
                    return Module::fromArray($module_name, $module_data);
                }
            }

            return null;
        }

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'API' => $this->API->toArray(),
                'POLICY' => $this->Policies->toArray(),
                'MODULES' => $this->Modules
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @param string $version
         * @return Configuration
         */
        public static function fromArray(array $data, string $version): Configuration
        {
            $ConfigurationObject = new Configuration();

            $ConfigurationObject->Version = $version;

            if(isset($data['API']))
            {
                $ConfigurationObject->API = API::fromArray($data['API']);
            }
            else
            {
                $ConfigurationObject->API = API::fromArray([]);
            }

            if(isset($data['POLICY']))
            {
                $ConfigurationObject->Policies = Policies::fromArray($data['POLICY']);
            }
            else
            {
                $ConfigurationObject->Policies = Policies::fromArray([]);
            }

            if(isset($data['MODULES']))
            {
                $ConfigurationObject->Modules = $data['MODULES'];
            }
            else
            {
                $ConfigurationObject->Modules = [];
            }

            return $ConfigurationObject;
        }
    }