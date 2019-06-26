<?php

    namespace AnalyticsManager\Abstracts;

    /**
     * Class ExceptionCodes
     * @package AnalyticsManager\Abstracts
     */
    abstract class ExceptionCodes
    {
        const InvalidDayException = 100;
        const ObjectNotAvailableException = 101;
        const InvalidHourException = 102;
        const DatabaseException = 103;
        const InvalidRecordSearchMethodException = 104;
        const RecordNotFoundException = 105;
        const RecordAlreadyExistsException = 106;
    }