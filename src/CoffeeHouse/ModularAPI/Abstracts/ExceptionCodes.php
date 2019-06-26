<?php

    namespace ModularAPI\Abstracts;

    /**
     * Class ExceptionCodes
     * @package ModularAPI\Abstracts
     */
    abstract class ExceptionCodes
    {
        const AccessKeyExpiredException = 100;
        const UsageExceededException = 101;
        const DatabaseNotEstablishedException = 102;
        const DatabaseException = 103;
        const UnsupportedSearchMethodException = 104;
        const NoResultsFoundException = 105;
        const AccessKeyNotFoundException = 106;
        const InvalidAccessKeyStatusException = 107;
        const UnsupportedClientException = 108;
        const InvalidRequestQueryException = 109;
        const MissingParameterException = 110;
    }