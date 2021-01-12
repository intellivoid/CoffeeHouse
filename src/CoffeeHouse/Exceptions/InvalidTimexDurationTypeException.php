<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidTimexDurationTypeException
     * @package CoffeeHouse\Exceptions
     */
    class InvalidTimexDurationTypeException extends Exception
    {
        /**
         * InvalidTimexDurationTypeException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }