<?php

    namespace ModularAPI\Abstracts\HTTP\ResponseCode;

    /**
     * Class Successful
     * @package ModularAPI\Abstracts\HTTP\ResponseCode
     */
    abstract class Successful
    {
        /**
         * 200 OK
         *
         * The request has succeeded
         */
        const _200 = 200;

        /**
         * 201 Created
         *
         * The request has succeeded and a new resource has been created as a result of it. This is typically the
         * response sent after a POST request, or after some PUT requests.
         */
        const _201 = 201;

        /**
         * 202 Accepted
         *
         * The request has been received but not yet acted upon. It is non-committal, meaning that there is no way in
         * HTTP to later send an asynchronous response indicating the outcome of processing the request. It is intended
         * for cases where another process or server handles the request, or for batch processing.
         */
        const _202 = 202;

        /**
         * 203 Non-Authoritative Information
         *
         * This response code means returned meta-information set is not exact set as available from the origin server,
         * but collected from a local or a third party copy. Except this condition, 200 OK response should be preferred
         * instead of this response.
         */
        const _203 = 203;

        /**
         * 204 No Content
         *
         * There is no content to send for this request, but the headers may be useful. The user-agent may update its
         * cached headers for this resource with the new ones.
         */
        const _204 = 204;

        /**
         * 205 Reset Content
         *
         * This response code is sent after accomplishing request to tell user agent reset document view which sent this
         * request.
         */
        const _205 = 205;

        /**
         * 206 Partial Content
         *
         * This response code is used because of range header sent by the client to separate download into multiple
         * streams.
         */
        const _206 = 206;

        /**
         * 207 Multi-Status (WebDAV)
         *
         * A Multi-Status response conveys information about multiple resources in situations where multiple status codes
         * might be appropriate.
         */
        const _207 = 207;

        /**
         * 208 Multi-Status (WebDAV)
         *
         * Used inside a DAV: propstat response element to avoid enumerating the internal members of multiple bindings
         * to the same collection repeatedly.
         */
        const _208 = 208;

        /**
         * 226 IM Used (HTTP Delta encoding)
         *
         * The server has fulfilled a GET request for the resource, and the response is a representation of the result
         * of one or more instance-manipulations applied to the current instance.
         */
        const _226 = 226;
    }