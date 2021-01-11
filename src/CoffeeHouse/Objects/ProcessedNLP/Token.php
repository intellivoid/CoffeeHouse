<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP;

    /**
     * Class Token
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class Token
    {
        /**
         * The tokenized word
         *
         * @var string|null
         */
        public $Word;

        /**
         * The original text of the code
         *
         * @var string|null
         */
        public $OriginalText;

        /**
         * Lemmatization maps a word to its lemma (dictionary form). For instance, the word was is mapped to the word be.
         *
         * @var string|null
         */
        public $Lemma;

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
         * The identified part of speech text.
         *
         * @var string|null
         */
        public $PartOfSpeech;

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Token
         */
        public static function fromArray(array $data): Token
        {
            $TokenObject = new Token();

            if(isset($data["word"]))
                $TokenObject->Word = $data["word"];

            if(isset($data["originalText"]))
                $TokenObject->OriginalText = $data["originalText"];
            if(isset($data["original_text"]))
                $TokenObject->OriginalText = $data["original_text"];

            if(isset($data["lemma"]))
                $TokenObject->Lemma = $data["lemma"];

            if(isset($data["characterOffsetBegin"]))
                $TokenObject->CharacterOffsetBegin = (int)$data["characterOffsetBegin"];
            if(isset($data["offset_begin"]))
                $TokenObject->CharacterOffsetBegin = (int)$data["offset_begin"];

            if(isset($data["characterOffsetEnd"]))
                $TokenObject->CharacterOffsetEnd = (int)$data["characterOffsetEnd"];
            if(isset($data["offset_end"]))
                $TokenObject->CharacterOffsetEnd = (int)$data["offset_end"];

            if(isset($data["pos"]))
                $TokenObject->PartOfSpeech = $data["pos"];

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
                "original_text" => $this->OriginalText,
                "lemma" => $this->Lemma,
                "offset_begin" => $this->CharacterOffsetBegin,
                "offset_end" => $this->CharacterOffsetEnd,
                "pos" => $this->PartOfSpeech,
            ];
        }
    }