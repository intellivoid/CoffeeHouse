<?php


    namespace CoffeeHouse\Objects;


    /**
     * Class TelegramClient
     * @package CoffeeHouse\Objects
     */
    class TelegramClient
    {
        /**
         * Internal Database ID
         *
         * @var int
         */
        public $ID;

        /**
         * Telegram Chat ID
         *
         * @var string
         */
        public $ChatID;

        /**
         * The foreign session ID associated with this client
         *
         * @var string
         */
        public $ForeignSessionID;

        /**
         * The Unix Timestamp of when this client was last updated
         *
         * @var int
         */
        public $LastUpdated;

        /**
         * The Unix Timestamp of when this client was created
         *
         * @var int
         */
        public $Created;

        /**
         * Creates Array from Object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'chat_id' => $this->ChatID,
                'foreign_session_id' => $this->ForeignSessionID,
                'last_updated' => $this->LastUpdated,
                'created' => $this->Created
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return TelegramClient
         */
        public static function fromArray(array $data): TelegramClient
        {
            $TelegramClientObject = new TelegramClient();

            if(isset($data['id']))
            {
                $TelegramClientObject->ID = (int)$data['id'];
            }

            if(isset($data['chat_id']))
            {
                $TelegramClientObject->ChatID = $data['chat_id'];
            }

            if(isset($data['foreign_session_id']))
            {
                $TelegramClientObject->ForeignSessionID = $data['foreign_session_id'];
            }

            if(isset($data['last_updated']))
            {
                $TelegramClientObject->LastUpdated = (int)$data['last_updated'];
            }

            if(isset($data['created']))
            {
                $TelegramClientObject->Created = (int)$data['created'];
            }

            return $TelegramClientObject;
        }
    }