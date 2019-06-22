<?php

    namespace Longman\TelegramBot\Commands\SystemCommands;
    use CoffeeHouse\Bots\Cleverbot;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\ForeignSessionNotFoundException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\TelegramClientNotFoundException;
    use Longman\TelegramBot\Commands\SystemCommand;
    use Longman\TelegramBot\Entities\ServerResponse;
    use Longman\TelegramBot\Exception\TelegramException;
    use Longman\TelegramBot\Request;
    /**
     * Start command
     *
     * Gets executed when a user first starts using the bot.
     */
    class ChatCommand extends SystemCommand
    {
        /**
         * @var string
         */
        protected $name = 'chat';

        /**
         * @var string
         */
        protected $description = 'Send a chat message to the bot';

        /**
         * @var string
         */
        protected $usage = '/chat';

        /**
         * @var string
         */
        protected $version = '1.0.0';

        /**
         * @var bool
         */
        protected $private_only = false;

        /**
         * Command execute method
         *
         * @return ServerResponse
         * @throws DatabaseException
         * @throws TelegramClientNotFoundException
         * @throws TelegramException
         * @throws BotSessionException
         * @throws ForeignSessionNotFoundException
         * @throws InvalidSearchMethodException
         */
        public function execute()
        {
            $message = $this->getMessage();

            $CoffeeHouse = new CoffeeHouse();
            $TelegramClient = $CoffeeHouse->getTelegramClientManager()->syncClient($message->getChat()->getId());

            if(strlen($message->getText(true)) == 0)
            {
                $data = [
                    'chat_id' => $message->getChat()->getId(),
                    'text' => "Learn to use the chat command thx"
                ];

                return Request::sendMessage($data);
            }

            $Bot = new Cleverbot($CoffeeHouse);

            // Check if the Telegram Client has a session ID
            if($TelegramClient->ForeignSessionID == 'None')
            {
                $Bot->newSession('en');
                $TelegramClient->ForeignSessionID = $Bot->getSession()->SessionID;
                $CoffeeHouse->getTelegramClientManager()->updateClient($TelegramClient);
            }
            else
            {
                $Bot->loadSession($TelegramClient->ForeignSessionID);
                if(time() > $Bot->getSession()->Expires)
                {
                    $Bot->newSession('en');
                    $TelegramClient->ForeignSessionID = $Bot->getSession()->SessionID;
                    $CoffeeHouse->getTelegramClientManager()->updateClient($TelegramClient);
                }
            }

            $Output = $Bot->think($message->getText(true));

            $data = [
                'chat_id' => $message->getChat()->getId(),
                'text' =>
                    $Output . "\n\n\n" .
                    "== Debugging Information ==\n" .
                    "Session: " . $Bot->getSession()->SessionID . "\n\n" .
                    "Path Processing:\n\n" . str_ireplace('cb', 'synical_ai', json_encode($Bot->getSession()->Variables))
            ];

            return Request::sendMessage($data);

        }
    }