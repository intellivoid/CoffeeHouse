<?php

    namespace ModularAPI\Objects;
    use ModularAPI\Abstracts\HTTP\RequestMethod;

    /**
     * Class RequestQuery
     * @package ModularAPI\Objects
     */
    class RequestQuery
    {
        /**
         * The requested API Version
         *
         * @var string
         */
        public $Version;

        /**
         * The requested module
         *
         * @var string
         */
        public $Module;

        /**
         * The request method used for this request
         *
         * @var RequestMethod
         */
        public $RequestMethod;
    }