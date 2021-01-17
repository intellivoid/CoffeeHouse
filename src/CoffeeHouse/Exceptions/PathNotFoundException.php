<?php


    namespace CoffeeHouse\Exceptions;


    use Throwable;

    /**
     * Class PathNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class PathNotFoundException extends \Exception
    {
        /**
         * PathNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }