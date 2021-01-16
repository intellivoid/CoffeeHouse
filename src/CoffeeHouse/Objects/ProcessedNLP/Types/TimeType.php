<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP\Types;

    use CoffeeHouse\Abstracts\CoreNLP\NamedEntityAlternativeValueTypes;
    use CoffeeHouse\Exceptions\InvalidDateException;
    use CoffeeHouse\Exceptions\InvalidTimeException;

    /**
     * Class TimeType
     * @package CoffeeHouse\Objects
     */
    class TimeType
    {
        /**
         * @var string
         */
        public $ObjectType = NamedEntityAlternativeValueTypes::Time;

        /**
         * Hour in 24 hour format
         *
         * @var int|null
         */
        public $Hour;

        /**
         * Minute
         *
         * @var int|null
         */
        public $Minute;

        /**
         * @var int|null
         */
        public $Seconds;

        /**
         * Converts this TimeType into a Unix Timestamp
         *
         * @return int
         * @throws InvalidTimeException
         */
        public function toUnixTimestamp(): int
        {
            $date_format = $this->Hour . ":" . $this->Minute . ":" . $this->Seconds;
            $parsed = strtotime($date_format);

            if($parsed == false)
                throw new InvalidTimeException("The string '$parsed' cannot be parsed.");

            return (int)$parsed;
        }

        /**
         * Returns an array representation of this TimeType
         *
         * @return array
         * @throws InvalidTimeException
         */
        public function toArray(): array
        {
            return [
                "hour" => $this->Hour,
                "minute" => $this->Minute,
                "seconds" => $this->Seconds,
                "unix_timestamp" => $this->toUnixTimestamp()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return TimeType
         */
        public static function fromArray(array $data): TimeType
        {
            $TimeTypeObject = new TimeType();

            if(isset($data["hour"]))
                $TimeTypeObject->Hour = (int)$data["hour"];

            if(isset($data["minute"]))
                $TimeTypeObject->Minute = (int)$data["minute"];

            if(isset($data["seconds"]))
                $TimeTypeObject->Seconds = (int)$data["seconds"];

            return $TimeTypeObject;
        }
    }