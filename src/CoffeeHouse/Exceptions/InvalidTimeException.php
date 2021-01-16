<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidTimeException
     * @package CoffeeHouse\Exceptions
     */
    class InvalidTimeException extends Exception
    {
        /**
         * InvalidTimeException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }