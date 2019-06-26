<?php

    namespace ModularAPI\Abstracts\HTTP\ResponseCode;

    /**
     * Class ClientError
     * @package ModularAPI\Abstracts\HTTP\ResponseCode
     */
    abstract class ClientError
    {
        /**
         * 400 Bad Request
         *
         * This response means that server could not understand the request due to invalid syntax.
         */
        const _400 = 400;

        /**
         * 401 Unauthorized
         *
         * Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated".
         * That is, the client must authenticate itself to get the requested response.
         */
        const _401 = 401;

        /**
         * 402 Payment Required
         *
         * This response code is reserved for future use. Initial aim for creating this code was using it for digital
         * payment systems however this is not used currently.
         */
        const _402 = 402;

        /**
         * 403 Forbidden
         *
         * The client does not have access rights to the content, i.e. they are unauthorized, so server is
         * rejecting to give proper response. Unlike 401, the client's identity is known to the server.
         */
        const _403 = 403;

        /**
         * 404 Not Found
         *
         * The server can not find requested resource. In the browser, this means the URL is not recognized. In an API,
         * this can also mean that the endpoint is valid but the resource itself does not exist. Servers may also send
         * this response instead of 403 to hide the existence of a resource from an unauthorized client. This response
         * code is probably the most famous one due to its frequent occurrence on the web.
         */
        const _404 = 404;

        /**
         * 405 Method Not Allowed
         *
         * The request method is known by the server but has been disabled and cannot be used. For example, an API may
         * forbid DELETE-ing a resource. The two mandatory methods, GET and HEAD, must never be disabled and should not
         * return this error code.
         */
        const _405 = 405;

        /**
         * 406 Not Acceptable
         *
         * This response is sent when the web server, after performing server-driven content negotiation, doesn't find
         * any content following the criteria given by the user agent.
         */
        const _406 = 406;

        /**
         * 407 Proxy Authentication Required
         *
         * This is similar to 401 but authentication is needed to be done by a proxy.
         */
        const _407 = 407;

        /**
         * 408 Request Timeout
         *
         * This response is sent on an idle connection by some servers, even without any previous request by the client.
         * It means that the server would like to shut down this unused connection. This response is used much more since
         * some browsers, like Chrome, Firefox 27+, or IE9, use HTTP pre-connection mechanisms to speed up surfing. Also
         * note that some servers merely shut down the connection without sending this message.
         */
        const _408 = 408;

        /**
         * 409 Conflict
         *
         * This response is sent when a request conflicts with the current state of the server.
         */
        const _409 = 409;

        /**
         * 410 Gone
         *
         * This response would be sent when the requested content has been permanently deleted from server, with no
         * forwarding address. Clients are expected to remove their caches and links to the resource. The HTTP
         * specification intends this status code to be used for "limited-time, promotional services". APIs should not
         * feel compelled to indicate resources that have been deleted with this status code.
         */
        const _410 = 410;

        /**
         * 411 Length Required
         *
         * Server rejected the request because the Content-Length header field is not defined and the server requires it.
         */
        const _411 = 411;

        /**
         * 412 Precondition Failed
         *
         * The client has indicated preconditions in its headers which the server does not meet.
         */
        const _412 = 412;

        /**
         * 413 Payload Too Large
         *
         * Request entity is larger than limits defined by server; the server might close the connection or return an
         * Retry-After header field.
         */
        const _413 = 413;

        /**
         * 414 URI Too Long
         *
         * The URI requested by the client is longer than the server is willing to interpret.
         */
        const _414 = 414;

        /**
         * 415 Unsupported Media Type
         *
         * The media format of the requested data is not supported by the server, so the server is rejecting the request.
         */
        const _415 = 415;

        /**
         * 416 Requested Range Not Satisfiable
         *
         * The range specified by the Range header field in the request can't be fulfilled; it's possible that the range
         * is outside the size of the target URI's data.
         */
        const _416 = 416;

        /**
         * 417 Expectation Failed
         *
         * This response code means the expectation indicated by the Expect request header field can't be met by
         * the server.
         */
        const _417 = 417;

        /**
         * 418 I'm a teapot
         *
         * The server refuses the attempt to brew coffee with a teapot.
         */
        const _418 = 418;

        /**
         * 421 Misdirected Request
         *
         * The request was directed at a server that is not able to produce a response. This can be sent by a server
         * that is not configured to produce responses for the combination of scheme and authority that are included in
         * the request URI.
         */
        const _421 = 421;

        /**
         * 422 Unprocessable Entity (WebDAV)
         *
         * The request was well-formed but was unable to be followed due to semantic errors.
         */
        const _422 = 422;

        /**
         * 423 Locked (WebDAV)
         *
         * The resource that is being accessed is locked.
         */
        const _423 = 423;

        /**
         * 424 Failed Dependency (WebDAV)
         *
         * The request failed due to failure of a previous request.
         */
        const _424 = 424;

        /**
         * 425 Too Early
         *
         * Indicates that the server is unwilling to risk processing a request that might be replayed.
         */
        const _425 = 425;

        /**
         * 426 Upgrade Required
         *
         * The server refuses to perform the request using the current protocol but might be willing to do so after the
         * client upgrades to a different protocol. The server sends an Upgrade header in a 426 response to indicate the
         * required protocol(s).
         */
        const _426 = 426;

        /**
         * 428 Precondition Required
         *
         * The origin server requires the request to be conditional. Intended to prevent the 'lost update' problem,
         * where a client GETs a resource's state, modifies it, and PUTs it back to the server, when meanwhile a third
         * party has modified the state on the server, leading to a conflict.
         */
        const _428 = 428;

        /**
         * 429 Too Many Requests
         *
         * The user has sent too many requests in a given amount of time ("rate limiting").
         */
        const _429 = 429;

        /**
         * 431 Request Header Fields Too Large
         *
         * The server is unwilling to process the request because its header fields are too large. The request MAY be
         * resubmitted after reducing the size of the request header fields.
         */
        const _431 = 431;

        /**
         * 451 Unavailable For Legal Reasons
         *
         * The user requests an illegal resource, such as a web page censored by a government.
         */
        const _451 = 451;
    }