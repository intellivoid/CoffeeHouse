<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Exceptions;


    use CoffeeHouse\Abstracts\ExceptionCodes;
    use Exception;


    /**
     * Class LanguagePredictionCacheNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class LanguagePredictionCacheNotFoundException extends Exception
    {
        /**
         * LanguagePredictionCacheNotFoundException constructor.
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct()
        {
            parent::__construct("The requested language prediction cache is not registered", ExceptionCodes::LanguagePredictionCacheNotFoundException);
        }
    }