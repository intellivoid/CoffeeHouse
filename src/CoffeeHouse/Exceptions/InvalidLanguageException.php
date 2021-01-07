<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidLanguageException
     * @package CoffeeHouse\Exceptionss
     */
    class InvalidLanguageException extends Exception
    {
        /**
         * InvalidLanguageException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }