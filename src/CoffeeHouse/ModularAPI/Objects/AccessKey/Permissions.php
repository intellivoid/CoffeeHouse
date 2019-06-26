<?php

    namespace ModularAPI\Objects\AccessKey;

    /**
     * Class Permissions
     * @package ModularAPI\Objects\AccessKey
     */
    class Permissions
    {
        /**
         * If set to true, this allows the key to utilize all modules regardless of the permission that has been set
         *
         * @var bool
         */
        public $AllowAll;

        /**
         * The array of modules that the key has permission to utilize
         *
         * @var array
         */
        public $Modules;

        /**
         * Adds access permission to a module
         *
         * @param string $module
         */
        public function addPermission(string $module)
        {
            if (!in_array(strtoupper($module), $this->Modules))
            {
                $this->Modules[] = strtoupper($module);
            }
        }

        /**
         * Removes an existing access permission to a module
         *
         * @param string $module
         */
        public function removePermission(string $module)
        {
            $this->Modules = array_diff($this->Modules, [strtoupper($module)]);
        }

        /**
         * Determines if the key has permission to a module
         *
         * @param string $module
         * @return bool
         */
        public function hasPermission(string $module): bool
        {
            if (in_array(strtoupper($module), $this->Modules))
            {
                return True;
            }

            return false;
        }

        /**
         * Revokes all permissions
         */
        public function revokeAllPermissions()
        {
            $this->Modules = [];
        }

        /**
         * Loads a configuration
         *
         * @param array $data
         */
        public function loadConfiguration(array $data)
        {
            switch($data['type'])
            {
                case 'allow_all_permissions':
                    $this->Modules = array();
                    $this->AllowAll = true;
                    break;

                case 'module_permissions':
                    $this->Modules = array();
                    $this->AllowAll = false;
                    foreach($data['modules'] as $module)
                    {
                        $this->addPermission($module);
                    }
                    break;
            }
        }

        /**
         * Converts the object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'allow_all' => $this->AllowAll,
                'modules' => $this->Modules
            );
        }

        /**
         * Constructs an object from an array
         *
         * @param array $data
         * @return Permissions
         */
        public static function fromArray(array $data): Permissions
        {
            $PermissionsObject = new Permissions();

            if(isset($data['allow_all']))
            {
                $PermissionsObject->AllowAll = (bool)$data['allow_all'];
            }
            else
            {
                $PermissionsObject->AllowAll = false;
            }

            if(isset($data['modules']))
            {
                $PermissionsObject->Modules = $data['modules'];
            }
            else
            {
                $PermissionsObject->Modules = array();
            }

            return $PermissionsObject;
        }
    }