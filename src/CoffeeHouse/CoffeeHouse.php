<?php


    namespace CoffeeHouse;


    use CoffeeHouse\Abstracts\BotType;
    use CoffeeHouse\Bots\Cleverbot;
    use Exception;
    use InvalidArgumentException;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Abstracts' . DIRECTORY_SEPARATOR . 'BotType.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Bots' . DIRECTORY_SEPARATOR . 'Cleverbot.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Bots' . DIRECTORY_SEPARATOR . 'CleverbotSession.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'Utilities.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'BotThought.php');

    /**
     * Class CoffeeHouse
     * @package CoffeeHouse
     */
    class CoffeeHouse
    {
        /**
         * Creates a new Bot
         * @param BotType|int $type
         * @param mixed|null $arguments
         * @return Cleverbot|mixed
         * @throws Exception
         */
        public function create(int $type, $arguments = null)
        {
            switch($type)
            {
                case BotType::Cleverbot:
                    return new Cleverbot(
                        'http://cleverbot.com',
                        'https://www.cleverbot.com/webservicemin?uc=UseOfficialCleverbotAPI',
                        33
                    );

                case BotType::JabberWacky:
                    throw new Exception('Not Implanted');

                case BotType::PandoraBots:
                    throw new Exception('Not Implanted');

                default:
                    throw new InvalidArgumentException();
            }
        }
    }