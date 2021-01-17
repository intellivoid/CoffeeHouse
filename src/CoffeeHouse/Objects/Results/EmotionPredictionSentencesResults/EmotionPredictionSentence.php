<?php


    namespace CoffeeHouse\Objects\Results\EmotionPredictionSentencesResults;

    use CoffeeHouse\Objects\Results\EmotionPredictionResults;

    /**
     * Class EmotionPredictionSentence
     * @package CoffeeHouse\Objects\Results\EmotionPredictionSentencesResults
     */
    class EmotionPredictionSentence
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
         * The emotion prediction results for this sentence
         */
        public ?EmotionPredictionResults $EmotionPredictionResults;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "emotion_prediction" => $this->EmotionPredictionResults->toArray()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return EmotionPredictionSentence
         */
        public static function fromArray(array $data): EmotionPredictionSentence
        {
            $EmotionPredictionSentenceObject = new EmotionPredictionSentence();

            if(isset($data["text"]))
                $EmotionPredictionSentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $EmotionPredictionSentenceObject->OffsetBegin = (int)$data["offset_begin"];

            if(isset($data["offset_end"]))
                $EmotionPredictionSentenceObject->OffsetEnd = (int)$data["offset_end"];

            if(isset($data["emotion_prediction"]))
                $EmotionPredictionSentenceObject->EmotionPredictionResults = EmotionPredictionResults::fromArray($data["emotion_prediction"]);

            return $EmotionPredictionSentenceObject;
        }
    }