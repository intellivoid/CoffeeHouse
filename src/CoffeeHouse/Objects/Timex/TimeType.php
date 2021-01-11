<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\Timex;


    use CoffeeHouse\Exceptions\InvalidDateException;

    /**
     * Class TimeType
     * @package CoffeeHouse\Objects
     */
    class TimeType
    {
        /**
         * Hour in 24 hour format
         *
         * @var int
         */
        public $Hour;

        /**
         * Minute
         *
         * @var int
         */
        public $Minute;

        /**
         * @var int
         */
        public $Seconds;

        /**
         * Converts this TimeType into a Unix Timestamp
         *
         * @return int
         * @throws InvalidDateException
         */
        public function toUnixTimestamp(): int
        {
            $date_format = $this->Hour . ":" . $this->Minute . ":" . $this->Seconds;
            $parsed = strtotime($date_format);

            if($parsed == false)
                throw new InvalidDateException("The string '$parsed' cannot be parsed.");

            return (int)$parsed;
        }
    }