<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Exceptions;

    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;

    /**
     * Class NoResultsFoundException
     * @package CoffeeHouse\Exceptions
     */
    class NoResultsFoundException extends Exception
    {
        /**
         * NoResultsFoundException constructor.
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct()
        {
            parent::__construct("No results were found in the database", ExceptionCodes::NoResultsFoundException);
        }
    }