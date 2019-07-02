<?php


    namespace CoffeeHouse\Exceptions;

    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class PathScopeOutputNotFound
     * @package CoffeeHouse\Exceptions
     */
    class PathScopeOutputNotFound extends Exception
    {
        /**
         * PathScopeOutputNotFound constructor.
         */
        public function __construct()
        {
            parent::__construct("The output for the selected path scope was not found", ExceptionCodes::PathScopeOutputNotFound, null);
        }
    }