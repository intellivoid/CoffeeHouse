<?php

    namespace AnalyticsManager;

    use acm\acm;
    use AnalyticsManager\Managers\Manager;
    use Exception;
    use mysqli;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'ExceptionCodes.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'RecordSearchMethod.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidDayException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidHourException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidRecordSearchMethodException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'ObjectNotAvailableException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'RecordAlreadyExistsException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'RecordNotFoundException.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Managers' . DIRECTORY_SEPARATOR . 'Manager.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'DayData.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'MonthData.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'Record.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Utilities' . DIRECTORY_SEPARATOR . 'Builder.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Utilities' . DIRECTORY_SEPARATOR . 'Hashing.php');

    if(class_exists('ZiProto\ZiProto') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'ZiProto' . DIRECTORY_SEPARATOR . 'ZiProto.php');
    }

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    /**
     * Class AnalyticsManager
     * @package AnalyticsManager
     */
    class AnalyticsManager
    {
        /**
         * @var mysqli
         */
        private $database;

        /**
         * @var Manager
         */
        private $Manager;

        /**
         * @var acm
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * AnalyticsManager constructor.
         * @param string $database_name
         * @throws Exception
         */
        public function __construct(string $database_name)
        {
            $this->acm = new acm(__DIR__, 'CoffeeHouse');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');

            $this->database = new mysqli(
                $this->DatabaseConfiguration['Host'],
                $this->DatabaseConfiguration['Username'],
                $this->DatabaseConfiguration['Password'],
                $database_name,
                $this->DatabaseConfiguration['Port']
            );

            $this->Manager = new Manager($this);
        }

        /**
         * @return mysqli
         */
        public function getDatabase(): mysqli
        {
            return $this->database;
        }

        /**
         * @return Manager
         */
        public function getManager(): Manager
        {
            return $this->Manager;
        }

        /**
         * @return acm
         */
        public function getAcm(): acm
        {
            return $this->acm;
        }

    }