<?php


    namespace CoffeeHouse\Objects\ProcessedNLP\Types;


    use CoffeeHouse\Abstracts\CoreNLP\NamedEntityAlternativeValueTypes;
    use CoffeeHouse\Abstracts\CoreNLP\TimexDateDuration;
    use CoffeeHouse\Abstracts\CoreNLP\TimexDurationType;
    use CoffeeHouse\Abstracts\CoreNLP\TimexTimeDuration;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Exceptions\CannotDetermineDurationTypeException;
    use CoffeeHouse\Exceptions\InvalidTimexDateDurationException;
    use CoffeeHouse\Exceptions\InvalidTimexDurationTypeException;
    use CoffeeHouse\Exceptions\InvalidTimexTimeDurationException;
    use CoffeeHouse\Exceptions\TimexDurationParseException;

    /**
     * The duration result
     *
     * Class Duration
     * @package CoffeeHouse\Objects\ProcessedNLP\Types
     */
    class Duration
    {
        /**
         * @var string
         */
        public $ObjectType = NamedEntityAlternativeValueTypes::Duration;

        /**
         * @var string|TimexDurationType
         */
        public $DurationType;

        /**
         * @var string|TimexTimeDuration|TimexDateDuration
         */
        public $ValueType;

        /**
         * @var int
         */
        public $Value;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "duration_type" => $this->DurationType,
                "value_type" => $this->ValueType,
                "value" => $this->Value
            ];
        }

        /**
         * Constructs object from an array
         *
         * @param array $data
         * @return Duration
         */
        public static function fromArray(array $data): Duration
        {
            $DurationObject = new Duration();

            if(isset($data["duration_type"]))
                $DurationObject->DurationType = $data["duration_type"];

            if(isset($data["value_type"]))
                $DurationObject->ValueType = $data["value_type"];

            if(isset($data["value"]))
                $DurationObject->Value = $data["value"];

            return $DurationObject;
        }

        /**
         * Parses the Timex string format into a readable object
         *
         * @param string $input
         * @return Duration
         * @throws CannotDetermineDurationTypeException
         * @throws InvalidTimexDateDurationException
         * @throws InvalidTimexDurationTypeException
         * @throws InvalidTimexTimeDurationException
         * @throws TimexDurationParseException
         */
        public static function fromSyntax(string $input): Duration
        {
            $duration_object = new Duration();
            $parsed_type = Utilities::parseTimexDurationType($input);
            $duration_object->DurationType = $parsed_type;

            switch($parsed_type)
            {
                case TimexDurationType::Date:
                    $duration_object->ValueType = Utilities::parseTimexDateDurationType($input);
                    $duration_object->ValueType = Utilities::TimexDurationDateToString($duration_object->ValueType);
                    $duration_object->Value = Utilities::parseTimexDurationValue($input);
                    break;

                case TimexDurationType::Time:
                    $duration_object->ValueType = Utilities::parseTimexTimeDurationType($input);
                    $duration_object->ValueType = Utilities::TimexDurationTimeToString($duration_object->ValueType);
                    $duration_object->Value = Utilities::parseTimexDurationValue($input);
                    break;

                case TimexDurationType::SeveralYears:
                case TimexDurationType::SeveralWeeks:
                case TimexDurationType::SeveralMonths:
                case TimexDurationType::SeveralDays:
                    $duration_object->ValueType = Utilities::parseTimexDateDurationType($input);
                    $duration_object->ValueType = Utilities::TimexDurationDateToString($duration_object->ValueType);
                    $duration_object->Value = null;
                    break;

                case TimexDurationType::SeveralSeconds:
                case TimexDurationType::SeveralMinutes:
                case TimexDurationType::SeveralMilliseconds:
                case TimexDurationType::SeveralHours:
                    $duration_object->ValueType = Utilities::parseTimexTimeDurationType($input);
                    $duration_object->ValueType = Utilities::TimexDurationTimeToString($duration_object->ValueType);
                    $duration_object->Value = null;
                    break;
            }

            $duration_object->DurationType = Utilities::TimexDurationTypeToString($duration_object->DurationType);
            return $duration_object;
        }
    }