<?php

    namespace ModularAPI\Abstracts;

    /**
     * Class AccessKeyStatus
     * @package ModularAPI\Abstracts
     */
    abstract class AccessKeyStatus
    {
        /**
         * The key is activated and available for use
         */
        const Activated = 0;

        /**
         * The key is suspended and it cannot be used
         */
        const Suspended = 1;

        /**
         * The key is limited from some actions
         */
        const Limited = 2;
    }