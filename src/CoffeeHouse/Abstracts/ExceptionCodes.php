<?php


    namespace CoffeeHouse\Abstracts;

    /**
     * Class ExceptionCodes
     * @package CoffeeHouse\Abstracts
     */
    abstract class ExceptionCodes
    {
        const BotSessionException = 100;

        const DatabaseException = 101;

        const InvalidSearchMethodException = 102;

        const ForeignSessionNotFoundException = 103;

        const InvalidMessageException = 104;

        /** @deprecated  */
        const TelegramClientNotFoundException = 105;

        /** @deprecated  */
        const ApiPlanNotFoundException = 106;

        /** @deprecated  */
        const InvalidApiPlanTypeException = 107;

        /** @deprecated  */
        const PathScopeOutputNotFound = 108;

        const UserSubscriptionNotFoundException = 109;
    }