<?php


    namespace CoffeeHouse\Objects\ProcessedNLP\Types;

    use CoffeeHouse\Abstracts\CoreNLP\NamedEntityAlternativeValueTypes;
    use CoffeeHouse\Exceptions\InvalidDateException;
    use CoffeeHouse\Exceptions\InvalidTimeException;

    /**
     * Class DateTimeType
     * @package CoffeeHouse\Objects\ProcessedNLP\Types
     */
    class DateTimeType
    {
        /**
         * @var string
         */
        public $ObjectType = NamedEntityAlternativeValueTypes::DateTime;

        /**
         * @var DateType|null
         */
        public $DateType;

        /**
         * @var TimeType|null
         */
        public $TimeType;

        /**
         * Returns a Unix Timestamp representation of this DateTimeType
         *
         * @return int
         * @throws InvalidDateException
         */
        public function toUnixTimestamp(): int
        {
            $date_format = $this->DateType->Year . "-" . $this->DateType->Month . "-" . $this->DateType->Day;
            $time_format = $this->TimeType->Hour . ":" . $this->TimeType->Minute . ":" . $this->TimeType->Seconds;

            $parsed = strtotime($date_format . " " . $time_format);

            if($parsed == false)
                throw new InvalidDateException("The string '$parsed' cannot be parsed.");

            return (int)$parsed;
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @throws InvalidDateException
         * @throws InvalidTimeException
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            return [
                "date" => $this->DateType->toArray(),
                "time" => $this->TimeType->toArray(),
                "unix_timestamp" => $this->toUnixTimestamp()
            ];
        }

        /**
         * Constructs this object from an array
         *
         * @param array $data
         * @return DateTimeType
         */
        public static function fromArray(array $data): DateTimeType
        {
            $DateTimeTypeObject = new DateTimeType();

            if(isset($data["date"]))
                $DateTimeTypeObject->DateType = DateType::fromArray($data["date"]);

            if(isset($data["time"]))
                $DateTimeTypeObject->TimeType = DateType::fromArray($data["time"]);

            return $DateTimeTypeObject;
        }
    }