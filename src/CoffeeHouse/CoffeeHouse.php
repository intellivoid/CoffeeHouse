<?php


    namespace CoffeeHouse;

    use acm\acm;
    use CoffeeHouse\Managers\ChatDialogsManager;
    use CoffeeHouse\Managers\ForeignSessionsManager;
    use CoffeeHouse\Managers\TelegramClientManager;
    use Exception;
    use mysqli;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'ExceptionCodes.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'ForeignSessionSearchMethod.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Bots' . DIRECTORY_SEPARATOR . 'Cleverbot.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Hashing.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Utilities.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Validation.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'BotSessionException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'DatabaseException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'ForeignSessionNotFoundException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidMessageException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'InvalidSearchMethodException.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Exceptions' . DIRECTORY_SEPARATOR . 'TelegramClientNotFoundException.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Managers' . DIRECTORY_SEPARATOR . 'ChatDialogsManager.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Managers' . DIRECTORY_SEPARATOR . 'ForeignSessionsManager.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Managers' . DIRECTORY_SEPARATOR . 'TelegramClientManager.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'BotThought.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'ForeignSession.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'HttpResponse.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'TelegramClient.php');

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
         * @var ForeignSessionsManager
         */
        private $ForeignSessionsManager;

        /**
         * @var ChatDialogsManager
         */
        private $ChatDialogsManager;

        /**
         * @var TelegramClientManager
         */
        private $TelegramClientManager;

        /**
         * @var mixed
         */
        private $TelegramConfiguration;

        /**
         * CoffeeHouse constructor.
         * @throws Exception
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'CoffeeHouse');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->TelegramConfiguration = $this->acm->getConfiguration('Telegram');
            $this->database = null;

            $this->ForeignSessionsManager = new ForeignSessionsManager($this);
            $this->ChatDialogsManager = new ChatDialogsManager($this);
            $this->TelegramClientManager = new TelegramClientManager($this);
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

        /**
         * @return ForeignSessionsManager
         */
        public function getForeignSessionsManager(): ForeignSessionsManager
        {
            return $this->ForeignSessionsManager;
        }

        /**
         * @return ChatDialogsManager
         */
        public function getChatDialogsManager(): ChatDialogsManager
        {
            return $this->ChatDialogsManager;
        }

        /**
         * @return TelegramClientManager
         */
        public function getTelegramClientManager(): TelegramClientManager
        {
            return $this->TelegramClientManager;
        }

        /**
         * @return mixed
         */
        public function getTelegramConfiguration()
        {
            return $this->TelegramConfiguration;
        }

    }