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
        const TelegramClientNotFoundException = 105;
        const ApiPlanNotFoundException = 106;
        const InvalidApiPlanTypeException = 107;
        const PathScopeOutputNotFound = 108;
    }