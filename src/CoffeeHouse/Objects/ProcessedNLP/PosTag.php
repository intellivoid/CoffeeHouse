<?php


    namespace CoffeeHouse\Objects\ProcessedNLP;

    use CoffeeHouse\Abstracts\CoreNLP\PartOfSpeechTag;

    /**
     * Class PosTag
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class PosTag
    {
        /**
         * The tokenized word
         *
         * @var string|null
         */
        public ?string $Word;

        /**
         * The character offset begin index
         *
         * @var int|null
         */
        public ?int $CharacterOffsetBegin;

        /**
         * The character offset end index
         *
         * @var int|null
         */
        public ?int $CharacterOffsetEnd;

        /**
         * The identified part of speech text.
         *
         * @var string|PartOfSpeechTag|null
         */
        public ?string $Value;

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return PosTag
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): PosTag
        {
            $TokenObject = new PosTag();

            if(isset($data["word"]))
                $TokenObject->Word = $data["word"];

            if(isset($data["characterOffsetBegin"]))
                $TokenObject->CharacterOffsetBegin = (int)$data["characterOffsetBegin"];
            if(isset($data["offset_begin"]))
                $TokenObject->CharacterOffsetBegin = (int)$data["offset_begin"];

            if(isset($data["characterOffsetEnd"]))
                $TokenObject->CharacterOffsetEnd = (int)$data["characterOffsetEnd"];
            if(isset($data["offset_end"]))
                $TokenObject->CharacterOffsetEnd = (int)$data["offset_end"];

            if(isset($data["pos"]))
                $TokenObject->Value = $data["pos"];

            if(isset($data["value"]))
                $TokenObject->Value = $data["value"];

            return $TokenObject;
        }

        /**
         * Returns an array representation of this value
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "word" => $this->Word,
                "offset_begin" => $this->CharacterOffsetBegin,
                "offset_end" => $this->CharacterOffsetEnd,
                "value" => $this->Value,
            ];
        }
    }