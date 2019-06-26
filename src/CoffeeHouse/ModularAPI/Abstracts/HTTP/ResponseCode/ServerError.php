<?php

    namespace ModularAPI\Abstracts\HTTP\ResponseCode;

    /**
     * Class ServerError
     * @package ModularAPI\Abstracts\HTTP\ResponseCode
     */
    abstract class ServerError
    {
        /**
         * 500 Internal Server Error
         *
         * The server has encountered a situation it doesn't know how to handle.
         */
        const _500 = 500;

        /**
         * 501 Not Implemented
         *
         * The request method is not supported by the server and cannot be handled. The only methods that servers are
         * required to support (and therefore that must not return this code) are GET and HEAD.
         */
        const _501 = 501;

        /**
         * 502 Bad Gateway
         *
         * This error response means that the server, while working as a gateway to get a response needed to handle the
         * request, got an invalid response.
         */
        const _502 = 502;

        /**
         * 503 Service Unavailable
         *
         * The server is not ready to handle the request. Common causes are a server that is down for maintenance or
         * that is overloaded. Note that together with this response, a user-friendly page explaining the problem should
         * be sent. This responses should be used for temporary conditions and the Retry-After: HTTP header should,
         * if possible, contain the estimated time before the recovery of the service. The webmaster must also take care
         * about the caching-related headers that are sent along with this response, as these temporary condition responses
         * should usually not be cached.
         */
        const _503 = 503;

        /**
         * 504 Gateway Timeout
         *
         * This error response is given when the server is acting as a gateway and cannot get a response in time.
         */
        const _504 = 504;

        /**
         * 505 HTTP Version Not Supported
         *
         * The HTTP version used in the request is not supported by the server.
         */
        const _505 = 505;

        /**
         * 506 Variant Also Negotiates
         *
         * The server has an internal configuration error: transparent content negotiation for the request results in a
         * circular reference.
         */
        const _506 = 506;

        /**
         * 507 Insufficient Storage
         *
         * The server has an internal configuration error: the chosen variant resource is configured to engage in
         * transparent content negotiation itself, and is therefore not a proper end point in the negotiation process.
         */
        const _507 = 507;

        /**
         * 508 Loop Detected (WebDAV)
         *
         * The server detected an infinite loop while processing the request.
         */
        const _508 = 508;

        /**
         * 510 Not Extended
         *
         * Further extensions to the request are required for the server to fulfill it.
         */
        const _510 = 510;

        /**
         * 511 Network Authentication Required
         *
         * The 511 status code indicates that the client needs to authenticate to gain network access.
         */
        const _511 = 511;
    }