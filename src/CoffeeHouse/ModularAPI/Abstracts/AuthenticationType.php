<?php

    namespace ModularAPI\Abstracts;

    /**
     * Class AuthenticationType
     * @package ModularAPI\Abstracts
     */
    abstract class AuthenticationType
    {
        /**
         * The user authenticated with a API Key
         */
        const APIKey = 'API_KEY';

        /**
         * The user authenticated with a certificate
         */
        const Certificate = 'CERTIFICATE';

        /**
         * If an error occurred while trying to determine the authentication type, this is thrown
         */
        const InvalidAuthentication = 'INVALID';

        /**
         * No authentication was used
         */
        const None = 'NONE';

    }