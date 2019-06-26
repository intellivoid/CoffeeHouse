<?php

namespace AnalyticsManager\Exceptions;

use AnalyticsManager\Abstracts\ExceptionCodes;
use Exception;

/**
 * Class RecordNotFoundException
 * @package AnalyticsManager\Exceptions
 */
class RecordNotFoundException extends Exception
{
    /**
     * RecordNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('The record was not found in the database', ExceptionCodes::RecordNotFoundException, null);
    }
}