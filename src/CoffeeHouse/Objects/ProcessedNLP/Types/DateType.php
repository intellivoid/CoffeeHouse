<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP\Types;

    use CoffeeHouse\Abstracts\CoreNLP\NamedEntityAlternativeValueTypes;
    use CoffeeHouse\Exceptions\InvalidDateException;

    /**
     * Class DateType
     * @package CoffeeHouse\Objects
     */
    class DateType
    {
        /**
         * @var string
         */
        public $ObjectType = NamedEntityAlternativeValueTypes::Date;

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

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @throws InvalidDateException
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            return [
                "day" => $this->Day,
                "month" => $this->Month,
                "year" => $this->Year,
                "unix_timestamp" => $this->toUnixTimestamp()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return DateType
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function fromArray(array $data): DateType
        {
            $DateTypeObject = new DateType();

            if(isset($data["day"]))
                $DateTypeObject->Day = (int)$data["day"];

            if(isset($data["month"]))
                $DateTypeObject->Month = (int)$data["month"];

            if(isset($data["year"]))
                $DateTypeObject->Year = (int)$data["year"];

            return $DateTypeObject;
        }
    }