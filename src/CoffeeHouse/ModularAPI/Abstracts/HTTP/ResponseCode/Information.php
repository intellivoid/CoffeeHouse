<?php

    namespace ModularAPI\Abstracts\HTTP\ResponseCode;

    /**
     * Class Information
     * @package ModularAPI\Abstracts\HTTP\ResponseCode
     */
    abstract class Information
    {
        /**
         * 100 Continue
         *
         * This interim response indicates that everything so far is OK and that the client should continue with the
         * request or ignore it if it is already finished.
         */
        const _100 = 100;

        /**
         * 101 Switching Protocol
         *
         * This code is sent in response to an Upgrade request header by the client, and indicates the protocol the
         * server is switching to.
         */
        const _101 = 101;

        /**
         * 102 Processing (WebDAV)
         *
         * This code indicates that the server has received and is processing the request, but no response is available
         * yet.
         */
        const _102 = 102;

        /**
         * 103 Early Hints
         *
         * This status code is primarily intended to be used with the Link header to allow the user agent to start
         * preloading resources while the server is still preparing a response.
         */
        const _103 = 103;
    }