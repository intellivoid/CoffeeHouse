<?php


    namespace CoffeeHouse\Objects\Results\SpamPredictionSentencesResults;


    use CoffeeHouse\Objects\Results\SpamPredictionResults;

    /**
     * Class SpamPredictionSentence
     * @package CoffeeHouse\Objects\Results\SpamPredictionSentencesResults
     */
    class SpamPredictionSentence
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
         * The Spam Prediction Results for this sentence
         *
         * @var SpamPredictionResults|null
         */
        public ?SpamPredictionResults $SpamPredictionResults;

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
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "spam_prediction" => $this->SpamPredictionResults->toArray()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SpamPredictionSentence
         */
        public static function fromArray(array $data): SpamPredictionSentence
        {
            $SpamPredictionSentenceObject = new SpamPredictionSentence();

            if(isset($data["text"]))
                $SpamPredictionSentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $SpamPredictionSentenceObject->OffsetBegin = (int)$data["offset_begin"];

            if(isset($data["offset_end"]))
                $SpamPredictionSentenceObject->OffsetEnd = (int)$data["offset_end"];

            if(isset($data["spam_prediction"]))
                $SpamPredictionSentenceObject->SpamPredictionResults = SpamPredictionResults::fromArray($data["spam_prediction"]);

            return $SpamPredictionSentenceObject;
        }
    }