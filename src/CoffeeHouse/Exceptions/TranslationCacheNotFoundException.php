<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class TranslationCacheNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class TranslationCacheNotFoundException extends Exception
    {
        /**
         * TranslationCacheNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }