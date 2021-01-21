<?php


    namespace CoffeeHouse\Abstracts;

    /**
     * Class LocalSessionSearchMethod
     * @package CoffeeHouse\Abstracts
     */
    abstract class LocalSessionSearchMethod
    {
        const ById = "id";

        const ByForeignSessionId = "foreign_session_id";
    }