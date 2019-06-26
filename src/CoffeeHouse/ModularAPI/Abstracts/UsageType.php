<?php

    namespace ModularAPI\Abstracts;

    /**
     * Class UsageType
     * @package ModularAPI\Abstracts
     */
    abstract class UsageType
    {
        /**
         * Allows the user to make as many requests without any limitation
         */
        const Unlimited = 0;

        /**
         * Usage is limited by a quota
         */
        const UsageLimit = 1;

        /**
         * Usage is limited but automatically reset at every interval
         */
        const DateIntervalLimit = 2;

        /**
         * Valid until the expiry date
         */
        const ExpiryLimit = 3;
    }