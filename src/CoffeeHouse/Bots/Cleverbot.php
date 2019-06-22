<?php


    namespace CoffeeHouse\Bots;

    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Objects\BotThought;

    /**
     * Class _Cleverbot
     * @package CoffeeHouse\Bots
     */
    class Cleverbot
    {

        /**
         * @var mixed
         */
        private $baseUrl;

        /**
         * @var mixed
         */
        private $serviceUrl;

        /**
         * @var CleverbotSession
         */
        private $Session;

        /**
         * Cleverbot constructor.
         */
        public function __construct()
        {
            $this->baseUrl = 'http://cleverbot.com';
            $this->serviceUrl = 'https://www.cleverbot.com/webservicemin?uc=UseOfficialCleverbotAPI';
        }

        /**
         * @return mixed
         */
        public function getBaseUrl()
        {
            return $this->baseUrl;
        }

        /**
         * @param mixed $baseUrl
         */
        public function setBaseUrl($baseUrl): void
        {
            $this->baseUrl = $baseUrl;
        }

        /**
         * @return mixed
         */
        public function getServiceUrl()
        {
            return $this->serviceUrl;
        }

        /**
         * @param mixed $serviceUrl
         */
        public function setServiceUrl($serviceUrl): void
        {
            $this->serviceUrl = $serviceUrl;
        }

        /**
         * @param string $language
         * @param null $session
         * @throws BotSessionException
         */
        public function createSession($language = 'en', $session = null)
        {
            $this->Session = new CleverbotSession($this, $language, $session);
        }

        /**
         * @param string $text
         * @return BotThought
         * @throws BotSessionException
         */
        public function think(string $text): BotThought
        {
            return $this->Session->thinkThought($text);
        }
    }