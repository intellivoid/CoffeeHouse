<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP\SentenceSplitResults;

    /**
     * Class Sentence
     * @package CoffeeHouse\Objects\Results\CoreNLP\SentenceSplitResults
     */
    class Sentence
    {
        /**
         * The text of the sentence
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * The offset beginning of this sentence
         *
         * @var int|null
         */
        public ?int $OffsetBegin;

        /**
         * The offset end of this sentence
         *
         * @var int|null
         */
        public ?int $OffsetEnd;


        /**
         * @return array
         */
        public function toArray(): array
        {
            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd
            ];
        }

        /**
         * Constructs an object from an array
         *
         * @param array $data
         * @return Sentence
         */
        public static function fromArray(array $data): Sentence
        {
            $SentenceObject = new Sentence();

            if(isset($data["text"]))
                $SentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $SentenceObject->OffsetBegin = $data["offset_begin"];

            if(isset($data["offset_end"]))
                $SentenceObject->OffsetEnd = $data["offset_end"];

            return $SentenceObject;
        }
    }