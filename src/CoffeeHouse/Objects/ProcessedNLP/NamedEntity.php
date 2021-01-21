<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP;

    use CoffeeHouse\Abstracts\CoreNLP\NamedEntityAlternativeValueTypes;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Exceptions\InvalidDateException;
    use CoffeeHouse\Exceptions\InvalidTimeException;
    use CoffeeHouse\Exceptions\InvalidTimexFormatException;
    use CoffeeHouse\Objects\ProcessedNLP\Types\DateTimeType;
    use CoffeeHouse\Objects\ProcessedNLP\Types\DateType;
    use CoffeeHouse\Objects\ProcessedNLP\Types\Duration;
    use CoffeeHouse\Objects\ProcessedNLP\Types\TimeType;
    use DateTime;
    use Exception;

    /**
     * Class NamedEntity
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class NamedEntity
    {
        /**
         * The text of the named entity
         *
         * @var string
         */
        public $Text;

        /**
         * The type named entity
         *
         * @var string|\CoffeeHouse\Abstracts\CoreNLP\NamedEntity
         */
        public $Type;

        /**
         * The confidence prediction value for this named entity
         *
         * @var float|int|null
         */
        public $Confidence;

        /**
         * The character offset begin index
         *
         * @var int|null
         */
        public $CharacterOffsetBegin;

        /**
         * The character offset end index
         *
         * @var int|null
         */
        public $CharacterOffsetEnd;

        /**
         * The parsed value from this named entity
         *
         * @var string|int|float|null
         */
        public $Value;

        /**
         * The alternative value parsed from this named entity
         *
         * @var DateType|TimeType|DateTimeType|Duration[]|null
         */
        public $AltValue;

        /**
         * @var string|NamedEntityAlternativeValueTypes
         */
        public $AltValueType;

        /**
         * NamedEntity constructor.
         */
        public function __construct()
        {
            $this->Text = null;
            $this->Type = null;
            $this->Confidence = null;
            $this->CharacterOffsetBegin = null;
            $this->CharacterOffsetEnd = null;
            $this->Value = null;
            $this->AltValue = null;
            $this->AltValueType = NamedEntityAlternativeValueTypes::None;
        }

        /**
         * Parses a NamedEntity from an array
         *
         * @param array $data
         * @return NamedEntity
         */
        public static function fromArray(array $data): NamedEntity
        {
            $NamedEntityObject = new NamedEntity();

            if(isset($data["text"]))
                $NamedEntityObject->Text = $data["text"];

            if(isset($data["characterOffsetBegin"]))
                $NamedEntityObject->CharacterOffsetBegin = $data["characterOffsetBegin"];
            if(isset($data["offset_begin"]))
                $NamedEntityObject->CharacterOffsetBegin = (int)$data["offset_begin"];

            if(isset($data["characterOffsetEnd"]))
                $NamedEntityObject->CharacterOffsetEnd = $data["characterOffsetEnd"];
            if(isset($data["offset_end"]))
                $NamedEntityObject->CharacterOffsetEnd = (int)$data["offset_end"];

            if(isset($data["ner"]))
                $NamedEntityObject->Type = Utilities::parseNerType($data["ner"]);
            if(isset($data["type"]))
                $NamedEntityObject->Type = $data["type"];

            if(isset($data["nerConfidence"]))
            {
                if(count($data["nerConfidence"]) > 0)
                    $NamedEntityObject->Confidence = array_values($data["nerConfidence"][0]);
            }
            if(isset($data["confidence"]))
            {
                $NamedEntityObject->Confidence = $data["confidence"];

            }

            if($NamedEntityObject->Confidence == null)
            {
                $NamedEntityObject->Confidence = (float)1;
            }
            elseif((float)$NamedEntityObject->Confidence < 0)
            {
                $NamedEntityObject->Confidence = (float)1;
            }

            if(isset($data["timex"]))
            {
                if($data["timex"]["value"] == "PRESENT_REF")
                {
                    $NamedEntityObject->Value = (int)time();
                    $NamedEntityObject->Type = \CoffeeHouse\Abstracts\CoreNLP\NamedEntity::CurrentTime;
                }
                else
                {
                    $success = null;

                    try
                    {
                        switch($data["timex"]["type"])
                        {
                            case "DATE":
                                try
                                {
                                    $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::Date;
                                    $NamedEntityObject->AltValue = Utilities::getTimexDate($data["timex"]["value"]);
                                    $NamedEntityObject->Value = $NamedEntityObject->AltValue->toUnixTimestamp();
                                }
                                catch(InvalidTimexFormatException | InvalidTimeException $e)
                                {
                                    $NamedEntityObject->Value = $data["timex"]["value"];
                                }

                                $success = true;
                                break;

                            case "TIME":

                                try
                                {
                                    $DateTypeObject = Utilities::getTimexDate($data["timex"]["value"]);
                                }
                                catch(InvalidTimexFormatException $e)
                                {
                                    $DateTypeObject = null;
                                }

                                $TimeTypeObject = Utilities::getTimexTime($data["timex"]["value"]);

                                /**
                                 * In some cases, the time may not carry a date input; so let's assume there's
                                 * only a TimeType, if that fails then the below then it will default the
                                 * text value.
                                 */

                                if($DateTypeObject == null)
                                {
                                    try
                                    {
                                        $NamedEntityObject->Value = $TimeTypeObject->toUnixTimestamp();
                                    }
                                    catch (InvalidTimeException $e)
                                    {
                                        unset($e);
                                    }

                                    $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::Time;
                                    $NamedEntityObject->AltValue = $TimeTypeObject;
                                }
                                else
                                {
                                    $NamedEntityObject->Value = Utilities::getUnixTimestamp($DateTypeObject, $TimeTypeObject);

                                    $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::DateTime;
                                    $NamedEntityObject->AltValue = new DateTime();
                                    $NamedEntityObject->AltValue->DateType = $DateTypeObject;
                                    $NamedEntityObject->AltValue->TimeType = $TimeTypeObject;
                                }

                                $success = true;
                                break;

                            case "DURATION":

                                $NamedEntityObject->AltValue = [];
                                $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::Duration;

                                $ExplodedValues = explode("/", $data["timex"]["value"]);

                                foreach($ExplodedValues as $value)
                                {
                                    try
                                    {
                                        $DurationObject = Duration::fromSyntax($value);
                                        $NamedEntityObject->AltValue[] = $DurationObject;

                                    }
                                    catch(Exception $e)
                                    {
                                        unset($e);
                                    }
                                }

                                if(isset($data["text"]))
                                    $NamedEntityObject->Value = $data["text"];


                                $success = true;
                                break;

                            default:
                                $success = false;
                        }
                    }
                    catch(InvalidTimexFormatException | InvalidDateException $e)
                    {
                        $success = false;
                    }

                    if($success == false)
                    {
                        if(isset($data["normalizedNer"]))
                        {
                            if($data["normalizedNer"] == "PRESENT_REF")
                            {
                                $NamedEntityObject->Value = (int)time();
                                $NamedEntityObject->Type = \CoffeeHouse\Abstracts\CoreNLP\NamedEntity::CurrentTime;
                            }
                            else
                            {
                                $NamedEntityObject->Value = $data["normalizedNer"];
                            }
                        }
                        else
                        {
                            $NamedEntityObject->Value = $data["text"];
                        }
                    }
                }
            }
            elseif(isset($data["normalizedNer"]))
            {
                if($data["normalizedNer"] == "PRESENT_REF")
                {
                    $NamedEntityObject->Value = (int)time();
                    $NamedEntityObject->Type = \CoffeeHouse\Abstracts\CoreNLP\NamedEntity::CurrentTime;
                }
                else
                {
                    $NamedEntityObject->Value = $data["normalizedNer"];
                }
            }
            elseif(isset($data["value"]))
            {
                $NamedEntityObject->Value = $data["value"];
            }
            else
            {
                $NamedEntityObject->Value = $data["text"];
            }

            if(isset($data["alt_type"]))
            {
                $NamedEntityObject->AltValue = $data["alt_type"];
            }

            if(isset($data["alt_value"]))
            {
                if(isset($data["alt_type"]))
                {
                    switch($data["alt_type"])
                    {
                        case NamedEntityAlternativeValueTypes::Time:
                            $NamedEntityObject->AltValue = TimeType::fromArray($data["alt_value"]);
                            break;

                        case NamedEntityAlternativeValueTypes::Date:
                            $NamedEntityObject->AltValue = DateType::fromArray($data["alt_value"]);
                            break;

                        case NamedEntityAlternativeValueTypes::DateTime:
                            $NamedEntityObject->AltValue = DateTimeType::fromArray($data["alt_value"]);
                            break;

                        case NamedEntityAlternativeValueTypes::Duration:
                            $NamedEntityObject->AltValue = Duration::fromArray($data["alt_value"]);
                            break;

                        default:
                            $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::None;
                            break;
                    }
                }
            }
            else
            {
                $NamedEntityObject->AltValueType = NamedEntityAlternativeValueTypes::None;
            }

            // Properly identify the entity type
            if(is_numeric($NamedEntityObject->Value))
            {
                if(ctype_digit($NamedEntityObject->Value) == false)
                {
                    // This may be a float
                    $NamedEntityObject->Value = (float)$NamedEntityObject->Value;
                }
                else
                {
                    // This may be a int
                    $NamedEntityObject->Value = (int)$NamedEntityObject->Value;
                }
            }
            else
            {
                $NamedEntityObject->Value = (string)$NamedEntityObject->Value;
            }

            // Sanity check
            if($NamedEntityObject->AltValue !== null)
            {
                $NamedEntityObject->AltValueType = $NamedEntityObject->AltValue->ObjectType;
            }

            return $NamedEntityObject;
        }

        /**
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $alt_value = null;
            $alt_value_type = null;

            if($this->AltValue !== null)
                try
                {
                    $alt_value = $this->AltValue->toArray();
                }
                catch (InvalidDateException | InvalidTimeException $e)
                {
                    // Failsafe!
                    $alt_value = null;
                    $alt_value_type = NamedEntityAlternativeValueTypes::None;
                }

            return [
                "text" => $this->Text,
                "type" => $this->Type,
                "confidence" => $this->Confidence,
                "offset_begin" => $this->CharacterOffsetBegin,
                "offset_end" => $this->CharacterOffsetEnd,
                "value" => $this->Value,
                "alt_type" => $alt_value_type,
                "alt_value" => $alt_value
            ];
        }
    }