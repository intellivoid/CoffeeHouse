<?php

    namespace ModularAPI\Abstracts\HTTP\ResponseCode;

    /**
     * Class Redirect
     * @package ModularAPI\Abstracts\HTTP\ResponseCode
     */
    abstract class Redirect
    {
        /**
         * 300 Multiple Choice
         *
         * The request has more than one possible response. The user-agent or user should choose one of them.
         * There is no standardized way of choosing one of the responses.
         */
        const _300 = 300;

        /**
         * 301 Moved Permanently
         *
         * This response code means that the URI of the requested resource has been changed. Probably, the new URI would
         * be given in the response.
         */
        const _301 = 301;

        /**
         * 302 Found
         *
         * This response code means that the URI of requested resource has been changed temporarily. New changes in the
         * URI might be made in the future. Therefore, this same URI should be used by the client in future requests.
         */
        const _302 = 302;

        /**
         * 303 See Other
         *
         * The server sent this response to direct the client to get the requested resource at another URI with a GET
         * request.
         */
        const _303 = 303;

        /**
         * 304 Not Modified
         *
         * This is used for caching purposes. It tells the client that the response has not been modified, so the client
         * can continue to use the same cached version of the response.
         */
        const _304 = 304;

        /**
         * 305 Use Proxy
         *
         * Was defined in a previous version of the HTTP specification to indicate that a requested response must be
         * accessed by a proxy. It has been deprecated due to security concerns regarding in-band configuration of a
         * proxy.
         */
        const _305 = 305;

        /**
         * 306 unused
         *
         * This response code is no longer used, it is just reserved currently. It was used in a previous version of the
         * HTTP 1.1 specification
         */
        const _306 = 306;

        /**
         * 307 Temporary Redirect
         *
         * The server sends this response to direct the client to get the requested resource at another URI with same
         * method that was used in the prior request. This has the same semantics as the 302 Found HTTP response code,
         * with the exception that the user agent must not change the HTTP method used: If a POST was used in the first
         * request, a POST must be used in the second request.
         */
        const _307 = 307;

        /**
         * 308 Permanent Redirect
         *
         * This means that the resource is now permanently located at another URI, specified by the Location:
         * HTTP Response header. This has the same semantics as the 301 Moved Permanently HTTP response code,
         * with the exception that the user agent must not change the HTTP method used: If a POST was used in the
         * first request, a POST must be used in the second request.
         */
        const _308 = 308;
    }