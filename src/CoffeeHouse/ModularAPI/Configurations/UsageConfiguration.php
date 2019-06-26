<?php

    namespace ModularAPI\Configurations;
    use ModularAPI\Abstracts\UsageType;

    /**
     * Class UsageConfiguration
     * @package ModularAPI\Configurations
     */
    class UsageConfiguration
    {
        /**
         * Allows the user to make as many requests without any limitation
         *
         * @return array
         */
        public static function unlimited(): array
        {
            return array(
                'type' => UsageType::Unlimited
            );
        }

        /**
         * Usage is limited by a quota
         *
         * @param int $limit The amount of requests that are going to be initially available to use
         * @return array
         */
        public static function usageLimit(int $limit): array
        {
            return array(
                'type' => UsageType::UsageLimit,
                'limit' => $limit
            );
        }

        /**
         * Usage is limited but automatically reset at every interval
         *
         * @param int $limit The amount of requests that can be made between each interval
         * @param int $resetInterval The interval that this limit is reset (seconds)
         * @return array
         */
        public static function dateIntervalLimit(int $limit, int $resetInterval): array
        {
            return array(
                'type' => UsageType::DateIntervalLimit,
                'limit' => $limit,
                'reset_interval' => $resetInterval
            );
        }

        /**
         * Valid until the expiry date
         *
         * @param int $expires
         * @return array
         */
        public static function expiryLimit(int $expires): array
        {
            return array(
                'type' => UsageType::ExpiryLimit,
                'expires' => $expires
            );
        }
    }