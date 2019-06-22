<?php


    namespace CoffeeHouse\Bots;

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
         * @var mixed
         */
        private $endIndex;

        /**
         * @var CleverbotSession
         */
        private $Session;

        /**
         * Cleverbot constructor.
         * @param $baseUrl
         * @param $serviceUrl
         * @param $endIndex
         */
        public function __construct($baseUrl, $serviceUrl, $endIndex)
        {
            $this->baseUrl = $baseUrl;
            $this->serviceUrl = $serviceUrl;
            $this->endIndex = $endIndex;
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
         * @return mixed
         */
        public function getEndIndex()
        {
            return $this->endIndex;
        }

        /**
         * @param mixed $endIndex
         */
        public function setEndIndex($endIndex): void
        {
            $this->endIndex = $endIndex;
        }

        /**
         * @param string $language
         * @param null $session
         */
        public function createSession($language = 'en', $session = null)
        {
            $this->Session = new CleverbotSession($this, $language, $session);
        }

        /**
         * @param string $text
         * @return BotThought
         */
        public function think(string $text): BotThought
        {
            return $this->Session->thinkThought($text);
        }
    }