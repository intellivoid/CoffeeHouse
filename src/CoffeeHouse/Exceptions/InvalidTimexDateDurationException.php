<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class InvalidTimexDateDurationException
     * @package CoffeeHouse\Exceptions
     */
    class InvalidTimexDateDurationException extends Exception
    {
        /**
         * InvalidTimexDateDurationException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }