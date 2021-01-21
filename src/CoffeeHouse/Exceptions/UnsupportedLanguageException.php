<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class UnsupportedLanguageException
     * @package CoffeeHouse\Exceptions
     */
    class UnsupportedLanguageException extends Exception
    {
        /**
         * UnsupportedLanguageException constructor.
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