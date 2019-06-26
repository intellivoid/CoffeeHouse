<?php
    /**
     * Created by PhpStorm.
     * User: Netkas
     * Date: 2/3/2019
     * Time: 4:17 PM
     */

    namespace ModularAPI\Objects;
    use ModularAPI\Abstracts\AuthenticationType;

    /**
     * Class RequestAuthentication
     * @package ModularAPI\Objects
     */
    class RequestAuthentication
    {
        /**
         * @var AuthenticationType
         */
        public $Type;

        /**
         * @var string
         */
        public $Key;

        /**
         * @var string
         */
        public $Certificate;

    }