<?php


    namespace CoffeeHouse\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class NsfwClassificationException
     * @package CoffeeHouse\Exceptions
     */
    class NsfwClassificationException extends Exception
    {
        /**
         * NsfwClassificationException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }