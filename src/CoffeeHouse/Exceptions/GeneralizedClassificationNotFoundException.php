<?php


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class GeneralizedClassificationNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class GeneralizedClassificationNotFoundException extends Exception
    {
        /**
         * GeneralizedClassificationNotFoundException constructor.
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct()
        {
            parent::__construct("The requested generalized classification record was not found in the database", ExceptionCodes::GeneralizedClassificationNotFoundException);
        }
    }