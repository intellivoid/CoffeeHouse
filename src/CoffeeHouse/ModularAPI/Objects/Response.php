<?php

    namespace ModularAPI\Objects;
    use ModularAPI\Abstracts\HTTP\ContentType;
    use ModularAPI\Abstracts\HTTP\FileType;
    use ModularAPI\Exceptions\UnsupportedClientException;

    /**
     * Class Response
     * @package ModularAPI\Objects
     */
    class Response
    {
        /**
         * The response type given to the client
         *
         * @var string
         */
        public $ResponseType;

        /**
         * The HTTP Response Code
         *
         * @var int
         */
        public $ResponseCode;

        /**
         * The content of the response
         *
         * @var mixed
         */
        public $Content;

        /**
         * Executes the response to the HTTP Client
         * @param string $referenceCode
         * @throws UnsupportedClientException
         */
        public function executeResponse(string $referenceCode = 'NONE')
        {
            switch($this->ResponseType)
            {
                case ContentType::application . '/' . FileType::json:
                    \ModularAPI\HTTP\Response::json($this->Content, $this->ResponseCode, $referenceCode);
                    break;

                default:
                    \ModularAPI\HTTP\Response::other($this->Content, $this->ResponseType, $this->ResponseCode);
                    break;
            }
        }
    }