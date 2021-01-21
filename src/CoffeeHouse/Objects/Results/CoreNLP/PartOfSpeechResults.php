<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\Results\CoreNLP;

    use CoffeeHouse\Objects\ProcessedNLP\Sentence;
    use CoffeeHouse\Objects\Results\CoreNLP\PartOfSpeechResults\PartOfSpeechSentence;

    /**
     * Class PartOfSpeechResults
     * @package CoffeeHouse\Objects\Results\CoreNLP
     */
    class PartOfSpeechResults
    {
        /**
         * The target text that was processed
         *
         * @var string|null
         */
        public $Text;

        /**
         * The sentences
         *
         * @var PartOfSpeechSentence[]|null
         */
        public $PartOfSpeechSentences;

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $pos_tags = [];

            foreach($this->PartOfSpeechSentences as $partOfSpeechSentence)
            {
                $pos_tags[] = $partOfSpeechSentence->toArray();
            }

            return [
                "text" => $this->Text,
                "sentences" => $pos_tags
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return PartOfSpeechResults
         */
        public static function fromArray(array $data): PartOfSpeechResults
        {
            $PartOfSpeechResultsObject = new PartOfSpeechResults();

            if(isset($data["text"]))
                $PartOfSpeechResultsObject->Text = $data["text"];

            if(isset($data["sentences"]))
            {
                $PartOfSpeechResultsObject->PartOfSpeechSentences = [];

                foreach($data["sentences"] as $pos_tag)
                    $PartOfSpeechResultsObject->PartOfSpeechSentences[] = PartOfSpeechSentence::fromArray($pos_tag);

            }

            return $PartOfSpeechResultsObject;
        }

        /**
         * Constructs object from the PartOfSpeechSentence Object
         *
         * @param string $text
         * @param Sentence[] $data
         * @return PartOfSpeechResults
         */
        public static function fromSentences(string $text, array $data): PartOfSpeechResults
        {
            $PartOfSpeechResultsObject = new PartOfSpeechResults();

            $PartOfSpeechResultsObject->Text = $text;
            $PartOfSpeechResultsObject->PartOfSpeechSentences = [];

            foreach($data as $sentence)
            {
                $pos_sentence = new PartOfSpeechSentence();
                $pos_sentence->Text = $sentence->Text;
                $pos_sentence->OffsetBegin = $sentence->OffsetBegin;
                $pos_sentence->OffsetEnd = $sentence->OffsetEnd;
                $pos_sentence->Tags = $sentence->PartOfSpeechTags;

                $PartOfSpeechResultsObject->PartOfSpeechSentences[] = $pos_sentence;

            }

            return $PartOfSpeechResultsObject;
        }

    }