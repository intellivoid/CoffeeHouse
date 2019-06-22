<?php


    namespace CoffeeHouse;

    use acm\acm;
    use Exception;
    use mysqli;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'ExceptionCodes.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Bots' . DIRECTORY_SEPARATOR . 'Cleverbot.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Bots' . DIRECTORY_SEPARATOR . 'CleverbotSession.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Utilities.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'BotSessionException.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'BotThought.php');

    if(class_exists('ZiProto\ZiProto') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'ZiProto' . DIRECTORY_SEPARATOR . 'ZiProto.php');
    }


    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'AutoConfig.php');

    /**
     * Class CoffeeHouse
     * @package CoffeeHouse
     */
    class CoffeeHouse
    {
        /**
         * @var mysqli
         */
        private $database;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var acm
         */
        private $acm;

        /**
         * CoffeeHouse constructor.
         * @throws Exception
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'CoffeeHouse');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->database = null;
        }

        /**
         * @return mysqli
         */
        public function getDatabase()
        {
            if($this->database == null)
            {
                $this->database = new mysqli(
                    $this->DatabaseConfiguration['Host'],
                    $this->DatabaseConfiguration['Username'],
                    $this->DatabaseConfiguration['Password'],
                    $this->DatabaseConfiguration['Name'],
                    $this->DatabaseConfiguration['Port']
                );
            }

            return $this->database;
        }
    }