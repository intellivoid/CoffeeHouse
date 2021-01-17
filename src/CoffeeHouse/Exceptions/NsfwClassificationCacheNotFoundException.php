<?php


    namespace CoffeeHouse\Exceptions;


    use Throwable;

    /**
     * Class NsfwClassificationCacheNotFoundException
     * @package CoffeeHouse\Exceptions
     */
    class NsfwClassificationCacheNotFoundException extends \Exception
    {
        /**
         * NsfwClassificationCacheNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }