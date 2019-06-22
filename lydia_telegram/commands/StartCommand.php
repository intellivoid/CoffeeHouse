<?php

    namespace Longman\TelegramBot\Commands\SystemCommands;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
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
    class StartCommand extends SystemCommand
    {
        /**
         * @var string
         */
        protected $name = 'start';

        /**
         * @var string
         */
        protected $description = 'Start command';

        /**
         * @var string
         */
        protected $usage = '/start';

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
         */
        public function execute()
        {
            $message = $this->getMessage();

            $CoffeeHouse = new CoffeeHouse();
            $TelegramClient = $CoffeeHouse->getTelegramClientManager()->syncClient($message->getChat()->getId());

            $data = [
                'chat_id' => $message->getChat()->getId(),
                'text' =>
                    "Hi! I'm Lydia, a Machine Learning chat bot that isn't based off a crappy AI/ML Library or Service` from Microsoft or any of the big companies.\n\n" .
                    "I was based off the classic SynicalAI Engine which was rewritten to CoffeeHouse, this whole project was created from scratch by @Intellivoid\n\n" .
                    "You can talk to me about any topic, just use the /chat command"
            ];

            return Request::sendMessage($data);

        }
    }