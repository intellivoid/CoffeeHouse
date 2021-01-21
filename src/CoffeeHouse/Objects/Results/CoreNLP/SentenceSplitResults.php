<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP;

    use CoffeeHouse\Objects\ProcessedNLP\Sentence;

    /**
     * Class SentenceSplitResults
     * @package CoffeeHouse\Objects\Results\CoreNLP
     */
    class SentenceSplitResults
    {
        /**
         * The target text that was processed
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * The sentences
         *
         * @var SentenceSplitResults\Sentence[]|null
         */
        public ?array $Sentences;

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            return [
                "text" => $this->Text,
                "sentences" => $this->Sentences
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SentenceSplitResults
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function fromArray(array $data): SentenceSplitResults
        {
            $SentenceSplitResultsObject = new SentenceSplitResults();

            if(isset($data["text"]))
                $SentenceSplitResultsObject->Text = $data["text"];

            if(isset($data["sentences"]))
                $SentenceSplitResultsObject->Sentences = $data["sentences"];

            return $SentenceSplitResultsObject;
        }

        /**
         * Constructs object from the PartOfSpeechSentence Object
         *
         * @param string $text
         * @param Sentence[] $data
         * @return SentenceSplitResults
         */
        public static function fromSentences(string $text, array $data): SentenceSplitResults
        {
            $SentenceSplitResultsObject = new SentenceSplitResults();
            $SentenceSplitResultsObject->Text = $text;
            $SentenceSplitResultsObject->Sentences = [];

            foreach($data as $sentence)
            {
                $SentenceObject = new SentenceSplitResults\Sentence();
                $SentenceObject->OffsetBegin = $sentence->OffsetBegin;
                $SentenceObject->OffsetEnd = $sentence->OffsetEnd;
                $SentenceObject->Text = $sentence->Text;

                $SentenceSplitResultsObject->Sentences[] = $SentenceObject;
            }

            return $SentenceSplitResultsObject;
        }
    }