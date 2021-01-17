<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class UnsupportedImageTypeException
     * @package CoffeeHouse\Exceptions
     */
    class UnsupportedImageTypeException extends Exception
    {
        /**
         * UnsupportedImageTypeException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }