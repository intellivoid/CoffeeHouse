<?php


    namespace CoffeeHouse\Managers;

    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\TelegramClientNotFoundException;
    use CoffeeHouse\Objects\TelegramClient;

    /**
     * Class TelegramClientManager
     * @package CoffeeHouse\Managers
     */
    class TelegramClientManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * TelegramClientManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Registers a client into the database
         *
         * @param string $chat_id
         * @return TelegramClient
         * @throws DatabaseException
         * @throws TelegramClientNotFoundException
         */
        public function registerClient(string $chat_id): TelegramClient
        {
            $chat_id = $this->coffeeHouse->getDatabase()->real_escape_string($chat_id);
            $foreign_session_id = $this->coffeeHouse->getDatabase()->real_escape_string('None');
            $created = (int)time();
            $last_updated = (int)$created;

            $Query = "INSERT INTO `telegram_clients` (chat_id, foreign_session_id, last_updated, created) VALUES ('$chat_id', '$foreign_session_id', $last_updated, $created)";
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);
            if($QueryResults)
            {
                return $this->getClient($chat_id);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Gets an existing client from the database
         *
         * @param string $chat_id
         * @return TelegramClient
         * @throws DatabaseException
         * @throws TelegramClientNotFoundException
         */
        public function getClient(string $chat_id): TelegramClient
        {
            $chat_id = $this->coffeeHouse->getDatabase()->real_escape_string($chat_id);
            $Query = "SELECT id, chat_id, foreign_session_id, last_updated, created FROM `telegram_clients` WHERE chat_id='$chat_id'";
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new TelegramClientNotFoundException();
                }
                else
                {
                    return(TelegramClient::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates an existing Telegram Client in the database
         *
         * @param TelegramClient $telegramClient
         * @return bool
         * @throws DatabaseException
         */
        public function updateClient(TelegramClient $telegramClient): bool
        {
            $chat_id = $this->coffeeHouse->getDatabase()->real_escape_string($telegramClient->ChatID);
            $foreign_session_id = $this->coffeeHouse->getDatabase()->real_escape_string($telegramClient->ForeignSessionID);
            $last_updated = time();

            $Query = "UPDATE `telegram_clients` SET foreign_session_id='$foreign_session_id', last_updated=$last_updated WHERE chat_id='$chat_id'";
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                return(True);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Creates the client if it doesn't exist
         *
         * @param string $chat_id
         * @return bool
         * @throws DatabaseException
         * @throws TelegramClientNotFoundException
         */
        public function syncClient(string $chat_id): TelegramClient
        {
            try
            {
                $Client = $this->getClient($chat_id);
            }
            catch(TelegramClientNotFoundException $telegramClientNotFoundException)
            {
                $Client = $this->registerClient($chat_id);
            }

            return $Client;
        }
    }