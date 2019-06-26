<?php

    namespace ModularAPI\Configurations;

    /**
     * Class PermissionsConfiguration
     * @package ModularAPI\Configurations
     */
    class PermissionsConfiguration
    {
        /**
         * Allow usage of all modules
         *
         * @return array
         */
        public static function allPermissionsAllowed(): array
        {
            return array(
                'type' => 'allow_all_permissions'
            );
        }

        /**
         * Specify Permissions for modules
         *
         * @param array $modules
         * @return array
         */
        public static function specifyPermissions(array $modules): array
        {
            $results = array('type' => 'module_permissions', 'modules' => []);

            foreach($modules as $module)
            {
                $results['modules'][] = strtoupper($module);
            }

            return $results;
        }
    }