<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP;

    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Exceptions\InvalidDateException;
    use CoffeeHouse\Exceptions\InvalidTimexFormatException;

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
                                $NamedEntityObject->Value = Utilities::getTimexDate($data["timex"]["value"])->toUnixTimestamp();
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
                                    $NamedEntityObject->Value = $TimeTypeObject->toUnixTimestamp();
                                }
                                else
                                {
                                    $NamedEntityObject->Value = Utilities::getUnixTimestamp($DateTypeObject, $TimeTypeObject);
                                }

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

            return $NamedEntityObject;
        }
    }