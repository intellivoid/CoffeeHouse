<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\Timex;

    use CoffeeHouse\Exceptions\InvalidDateException;

    /**
     * Class DateType
     * @package CoffeeHouse\Objects
     */
    class DateType
    {
        /**
         * The day of the month
         *
         * @var int|null
         */
        public $Day;

        /**
         * The month of the year
         *
         * @var int|null
         */
        public $Month;

        /**
         * The year
         *
         * @var int|null
         */
        public $Year;

        /**
         * Converts this DateType into a Unix Timestamp
         *
         * @return int
         * @throws InvalidDateException
         */
        public function toUnixTimestamp(): int
        {
            $date_format = $this->Year . "-" . $this->Month . "-" . $this->Day;
            $parsed = strtotime($date_format);

            if($parsed == false)
                throw new InvalidDateException("The string '$parsed' cannot be parsed.");

            return (int)$parsed;
        }
    }