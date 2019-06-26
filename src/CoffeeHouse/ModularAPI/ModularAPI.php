<?php

    namespace ModularAPI;

    use acm\acm;
    use Exception;
    use ModularAPI\DatabaseManager\Requests;
    use ModularAPI\Managers\AccessKeyManager;
    use mysqli;

    define('MODULAR_API', __DIR__ . DIRECTORY_SEPARATOR);

    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ResponseCode' . DIRECTORY_SEPARATOR . 'ClientError.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ResponseCode' . DIRECTORY_SEPARATOR . 'Information.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ResponseCode' . DIRECTORY_SEPARATOR . 'Redirect.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ResponseCode' . DIRECTORY_SEPARATOR . 'ServerError.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ResponseCode' . DIRECTORY_SEPARATOR . 'Successful.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'ContentType.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'FileType.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'HTTP' . DIRECTORY_SEPARATOR . 'RequestMethod.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'AccessKeySearchMethod.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'AccessKeyStatus.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'AuthenticationType.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'ExceptionCodes.php');
    include_once(MODULAR_API . 'Abstracts' . DIRECTORY_SEPARATOR . 'UsageType.php');
    include_once(MODULAR_API . 'Configurations' . DIRECTORY_SEPARATOR . 'PermissionsConfiguration.php');
    include_once(MODULAR_API . 'Configurations' . DIRECTORY_SEPARATOR . 'UsageConfiguration.php');
    include_once(MODULAR_API . 'DatabaseManager' . DIRECTORY_SEPARATOR . 'AccessKeys.php');
    include_once(MODULAR_API . 'DatabaseManager' . DIRECTORY_SEPARATOR . 'Requests.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccessKeyExpiredException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'AccessKeyNotFoundException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseNotEstablishedException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidAccessKeyStatusException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidRequestQueryException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'MissingParameterException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'NoResultsFoundException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'UnsupportedClientException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'UnsupportedSearchMethodException.php');
    include_once(MODULAR_API . 'Exceptions' . DIRECTORY_SEPARATOR . 'UsageExceededException.php');
    include_once(MODULAR_API . 'HTTP' . DIRECTORY_SEPARATOR . 'Headers.php');
    include_once(MODULAR_API . 'HTTP' . DIRECTORY_SEPARATOR . 'Request.php');
    include_once(MODULAR_API . 'HTTP' . DIRECTORY_SEPARATOR . 'Response.php');
    include_once(MODULAR_API . 'Managers' . DIRECTORY_SEPARATOR . 'AccessKeyManager.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKey' . DIRECTORY_SEPARATOR . 'Analytics.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKey' . DIRECTORY_SEPARATOR . 'Permissions.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKey' . DIRECTORY_SEPARATOR . 'Signatures.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKey' . DIRECTORY_SEPARATOR . 'Usage.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'AccessKey.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'API.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'Configuration.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'ExceptionDetails.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'Module.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'Parameter.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'Policies.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'RequestAuthentication.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'RequestQuery.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'RequestRecord.php');
    include_once(MODULAR_API . 'Objects' . DIRECTORY_SEPARATOR . 'Response.php');
    include_once(MODULAR_API . 'Utilities' . DIRECTORY_SEPARATOR . 'Builder.php');
    include_once(MODULAR_API . 'Utilities' . DIRECTORY_SEPARATOR . 'Checker.php');
    include_once(MODULAR_API . 'Utilities' . DIRECTORY_SEPARATOR . 'Hashing.php');

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    /**
     * Main AutoLoader for ModularAPI
     *
     * Class ModularAPI
     * @package ModularAPI
     */
    class ModularAPI
    {
        /**
         * The Database connection, null if Database connection isn't established
         *
         * @var null|mysqli
         */
        public $Database;

        /**
         * @var AccessKeyManager
         */
        private $AccessKeyManager;

        /**
         * @var DatabaseManager\Requests
         */
        private $RequestsLog;

        /**
         * @var acm
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var mixed
         */
        private $ModularApiConfiguration;

        /**
         * Constructs ModularAPI Library
         *
         * ModularAPI constructor.
         * @param bool $EstablishDatabaseConnection
         * @throws Exception
         */
        public function __construct(bool $EstablishDatabaseConnection = true)
        {
            $this->acm = new acm(__DIR__, 'CoffeeHouse');

            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->ModularApiConfiguration = $this->acm->getConfiguration('ModularAPI');

            if($EstablishDatabaseConnection == true)
            {
                $this->Database = new mysqli(
                    $this->DatabaseConfiguration['Host'],
                    $this->DatabaseConfiguration['Username'],
                    $this->DatabaseConfiguration['Password'],
                    $this->DatabaseConfiguration['Name'],
                    $this->DatabaseConfiguration['Port']
                );
            }
            else
            {
                $this->Database = null;
            }

            $this->AccessKeyManager = new AccessKeyManager($this);
            $this->RequestsLog = new DatabaseManager\Requests($this);
        }

        /**
         * Manages Access Keys
         *
         * @return AccessKeyManager
         */
        public function AccessKeys(): AccessKeyManager
        {
            return $this->AccessKeyManager;
        }

        /**
         * Manages Request Logs
         *
         * @return Requests
         */
        public function RequestsLog(): Requests
        {
            return $this->RequestsLog;
        }

        /**
         * @return mixed
         */
        public function getDatabaseConfiguration()
        {
            return $this->DatabaseConfiguration;
        }

        /**
         * @return mixed
         */
        public function getModularApiConfiguration()
        {
            return $this->ModularApiConfiguration;
        }

        /**
         * @return acm
         */
        public function getAcm(): acm
        {
            return $this->acm;
        }

    }