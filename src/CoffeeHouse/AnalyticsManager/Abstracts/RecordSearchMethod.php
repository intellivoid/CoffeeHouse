<?php

    namespace AnalyticsManager\Abstracts;

    /**
     * Class RecordSearchMethod
     * @package AnalyticsManager\Abstracts
     */
    abstract class RecordSearchMethod
    {
        /**
         * Searches records by ID
         */
        const byId = 'id';

        /**
         * Searches records by Public ID
         */
        const byPublicId = 'public_id';

        /**
         * Searches records by Name
         */
        const byName = 'name';
    }