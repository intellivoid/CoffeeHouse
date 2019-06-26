<?php

namespace AnalyticsManager\Exceptions;

use AnalyticsManager\Abstracts\ExceptionCodes;
use Exception;

/**
 * Class InvalidRecordSearchMethodException
 * @package AnalyticsManager\Exceptions
 */
class InvalidRecordSearchMethodException extends Exception
{
    /**
     * InvalidRecordSearchMethodException constructor.
     */
    public function __construct()
    {
        parent::__construct('The given search method is not valid for looking up records', ExceptionCodes::InvalidRecordSearchMethodException, null);
    }
}