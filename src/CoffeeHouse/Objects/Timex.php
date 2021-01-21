<?php


    namespace CoffeeHouse\Objects;


    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Exceptions\InvalidTimexFormatException;
    use CoffeeHouse\Objects\ProcessedNLP\Types\DateType;
    use CoffeeHouse\Objects\ProcessedNLP\Types\TimeType;
    use CoffeeHouse\Objects\Timex\Range;

    /**
     * Stores one TIMEX3 expression.  This class is used for both TimeAnnotator and
     * GUTimeAnnotator for storing information for TIMEX3 tags.
     *
     * This is a reimplementation of the Timex class from Java.
     *
     * Class Timex
     * @package CoffeeHouse\Objects
     */
    class Timex
    {
        /**
         * XML representation of the TIMEX tag
         *
         * @var string|null
         */
        private $xml;

        /**
         * TIMEX3 value attribute - Time value (given in extended ISO 8601 format).
         *
         * @var string|null
         */
        private $val;

        /**
         * Alternate representation for time value (not part of TIMEX3 standard).
         * used when value of the time expression cannot be expressed as a standard TIMEX3 value.
         *
         * @var string|null
         */
        private $altVal;

        /**
         * Actual text that make up the time expression
         *
         * @var string|null
         */
        private $text;

        /**
         * TIMEX3 type attribute - Type of the time expression (DATE, TIME, DURATION, or SET)
         *
         * @var string|null
         */
        private $type;

        /**
         * TIMEX3 tid attribute - TimeID.  ID to identify this time expression.
         * Should have the format of {@code t<integer>}
         *
         * @var string|null
         */
        private $tid;

        /**
         * TIMEX3 beginPoint attribute - integer indicating the TimeID of the begin time
         * that anchors this duration/range (-1 is not present).
         *
         * @var int|null
         */
        private $beginPoint;

        /**
         * TIMEX3 beginPoint attribute - integer indicating the TimeID of the end time
         * that anchors this duration/range (-1 is not present).
         *
         * @var int|null
         */
        private $endPoint;

        /**
         * Range begin/end/duration
         * (this is not part of the timex standard and is typically null, available if sutime.includeRange is true)
         *
         * @var Range|null
         */
        private $range;

        /**
         * @return string
         */
        public function value(): string
        {
            return $this->val;
        }

        /**
         * @return string
         */
        public function altVal(): string
        {
            return $this->altVal;
        }

        /**
         * @return string
         */
        public function text(): string
        {
            return $this->text;
        }

        /**
         * @return string
         */
        public function timexType(): string
        {
            return $this->type;
        }

        /**
         * @return string
         */
        public function tid(): string
        {
            return $this->tid;
        }

        /**
         * @return string
         */
        public function range(): string
        {
            return $this->range;
        }

        /**
         * @return int
         */
        public function beginPoint(): int
        {
            return $this->beginPoint;
        }

        /**
         * @return int
         */
        public function endPoint(): int
        {
            return $this->endPoint;
        }

        /**
         * Constructs from string
         *
         * @param string $type
         * @param string $val
         * @return Timex
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function withString(string $type, string $val): Timex
        {
            $TimexObject = new Timex();
            $TimexObject->val = $val;
            $TimexObject->type = $type;
            $TimexObject->beginPoint = -1;
            $TimexObject->endPoint = -1;

            if($val == null)
            {
                $TimexObject->xml = "<TIMEX3/>";
            }
            else
            {
                $TimexObject->xml = "<TIMEX3 val=\"" . $val . "\" type=\"" . $type . "\"/>";
            }

            return $TimexObject;
        }

        /**
         * Constructs using Timex Attributes
         *
         * @param string $type
         * @param string $val
         * @param string $altVal
         * @param string $tid
         * @param string $text
         * @param int $beginPoint
         * @param int $endPoint
         * @return Timex
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function withTimex(string $type, string $val, string $altVal, string $tid, string $text, int $beginPoint, int $endPoint): Timex
        {
            $TimexObject = new Timex();
            $TimexObject->type = $type;
            $TimexObject->val = $val;
            $TimexObject->altVal = $altVal;
            $TimexObject->tid = $tid;
            $TimexObject->text = $text;
            $TimexObject->beginPoint = $beginPoint;
            $TimexObject->endPoint = $endPoint;

            if($val == null)
            {
                $TimexObject->xml = "<TIMEX3/>";
            }
            else
            {
                $TimexObject->xml = "<TIMEX3 tid=\"" . $tid . "\" val=\"" . $val . "\" type=\"" . $type . "\">$text</TIMEX3>";
            }

            return $TimexObject;
        }

        /**
         * Constructs with XML data
         *
         * @param string $data
         * @return Timex
         */
        public static function withXML(string $data): Timex
        {
            $xml_data = Utilities::xml2array(simplexml_load_string($data));
            $TimexObject = new Timex();

            if(isset($xml_data[0]))
                $TimexObject->text = $xml_data[0];

            if(isset($xml_data["@attributes"]["tid"]))
                $TimexObject->tid = $xml_data["@attributes"]["tid"];

            if(isset($xml_data["@attributes"]["VAL"]))
                $TimexObject->tid = $xml_data["@attributes"]["VAL"];
            if(isset($xml_data["@attributes"]["val"]))
                $TimexObject->tid = $xml_data["@attributes"]["val"];
            if(isset($xml_data["@attributes"]["VALUE"]))
                $TimexObject->tid = $xml_data["@attributes"]["VALUE"];
            if(isset($xml_data["@attributes"]["value"]))
                $TimexObject->tid = $xml_data["@attributes"]["value"];

            if(isset($xml_data["@attributes"]["altVal"]))
                $TimexObject->altVal = $xml_data["@attributes"]["altVal"];
            if(isset($xml_data["@attributes"]["alt_value"]))
                $TimexObject->altVal = $xml_data["@attributes"]["alt_value"];

            if(isset($xml_data["@attributes"]["type"]))
                $TimexObject->type = $xml_data["@attributes"]["type"];
            if(isset($xml_data["@attributes"]["TYPE"]))
                $TimexObject->type = $xml_data["@attributes"]["TYPE"];

            if(isset($xml_data["@attributes"]["beginPoint"]))
                $TimexObject->beginPoint = $xml_data["@attributes"]["beginPoint"];
            if(isset($xml_data["@attributes"]["begin_point"]))
                $TimexObject->beginPoint = $xml_data["@attributes"]["begin_point"];

            if($TimexObject->beginPoint == null || strlen($TimexObject->beginPoint) == 0)
            {
                $TimexObject->beginPoint = -1;
            }
            else
            {
                $TimexObject->beginPoint = (int)$TimexObject->beginPoint;
            }

            if(isset($xml_data["@attributes"]["endPoint"]))
                $TimexObject->endPoint = $xml_data["@attributes"]["endPoint"];
            if(isset($xml_data["@attributes"]["end_point"]))
                $TimexObject->endPoint = $xml_data["@attributes"]["end_point"];

            if($TimexObject->endPoint == null || strlen($TimexObject->endPoint) == 0)
            {
                $TimexObject->endPoint = -1;
            }
            else
            {
                $TimexObject->endPoint = (int)$TimexObject->endPoint;
            }

            if(isset($xml_data["@attributes"]["range"]))
            {
                $range_raw = $xml_data["@attributes"]["range"];

                if(Utilities::startsWith($range_raw, "(") && Utilities::endsWith($range_raw, ")"))
                {
                    $range_raw = substr($range_raw, 1, strlen($range_raw) -1);
                }

                $parts = explode($range_raw, ",");

                $TimexObject->range = new Range(
                    count($parts) > 0? $parts[0] : (string)null,
                    count($parts) > 1? $parts[1] : (string)null,
                    count($parts) > 2? $parts[2] : (string)null
                );
            }

            return $TimexObject;
        }

        /**
         * Returns an XML representation of this Timex Value
         *
         * @return string
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public function toXml(): string
        {
            $results = "<TIMEX3 ";

            if($this->tid !== null)
                $results .= 'tid="' . $this->tid . '" ';

            if($this->value() !== null)
                $results .= 'value="' . $this->value() . '" ';

            if($this->altVal !== null)
                $results .= 'altVal="' . $this->altVal . '" ';

            if($this->type !== null)
                $results .= 'type="' . $this->type . '" ';

            if($this->beginPoint !== null)
                $results .= 'beginPoint="t' . $this->beginPoint . '" ';

            if($this->endPoint !== null)
                $results .= 'endPoint="t' . $this->endPoint . '" ';


            if($this->text == null)
            {
                $results .= '/>';
            }
            else
            {
                $results .= '/>' . $this->text . "</TIMEX3>";
            }

            return $results;
        }

        /**
         * Gets the current DateType for this Timex3 expression
         *
         * @return DateType
         * @throws InvalidTimexFormatException
         */
        public function getDate()
        {
            return Utilities::getTimexDate($this->val);
        }

        /**
         * Gets the current TimeType for this Timex3 expression
         *
         * @return TimeType
         * @throws InvalidTimexFormatException
         */
        public function getTime()
        {
            return Utilities::getTimexTime($this->val);
        }
    }