<?php


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Abstracts\EmotionType;

    /**
     * Class EmotionPredictionResults
     * @package CoffeeHouse\Objects\Results
     */
    class EmotionPredictionResults
    {
        /**
         * The top value from this result
         *
         * @var float|null
         */
        public ?float $TopValue;

        /**
         * The top emotion dictated from the value
         *
         * @var string|null
         */
        public ?string $TopEmotion;

        /**
         * Neutral Emotion
         *
         * @var float|null
         */
        public ?float $NeutralValue;

        /**
         * Happiness Emotion
         *
         * @var float|null
         */
        public ?float $HappinessValue;

        /**
         * Affection Emotion
         *
         * @var float|null
         */
        public ?float $AffectionValue;

        /**
         * Sadness Emotion
         *
         * @var float|null
         */
        public ?float $SadnessValue;

        /**
         * Anger Emotion
         *
         * @var float|null
         */
        public ?float $AngerValue;

        /**
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function valuesToArray(): array
        {
            return [
                EmotionType::Neutral => $this->NeutralValue,
                EmotionType::Happiness => $this->HappinessValue,
                EmotionType::Affection => $this->AffectionValue,
                EmotionType::Sadness => $this->SadnessValue,
                EmotionType::Anger => $this->AngerValue
            ];
        }

        /**
         * Updates the top value
         */
        public function updateTopValue()
        {
            $values = $this->valuesToArray();
            $top_emotion = null;
            $top_value = null;

            foreach($values as $emotion => $value)
            {
                if($top_value == null)
                {
                    $top_emotion = $emotion;
                    $top_value = $value;
                }
                else
                {
                    if($value > $top_value)
                    {
                        $top_emotion = $emotion;
                        $top_value = $value;
                    }
                }
            }

            $this->TopEmotion = $top_emotion;
            $this->TopValue = $top_value;
        }


        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            return [
                "top_value" => $this->TopValue,
                "top_emotion" => $this->TopEmotion,
                "values" => $this->valuesToArray()
            ];
        }

        /**
         * Constructs this object from an array
         *
         * @param array $data
         * @return EmotionPredictionResults
         */
        public static function fromArray(array $data): EmotionPredictionResults
        {
            $EmotionPredictionResultsObject = new EmotionPredictionResults();

            if(isset($data["top_value"]))
                $EmotionPredictionResultsObject->TopValue = $data["top_value"];

            if(isset($data["top_emotion"]))
                $EmotionPredictionResultsObject->TopEmotion = $data["top_emotion"];

            if(isset($data["values"]))
            {
                if(isset($data["values"][EmotionType::Neutral]))
                    $EmotionPredictionResultsObject->NeutralValue = $data["values"][EmotionType::Neutral];

                if(isset($data["values"][EmotionType::Happiness]))
                    $EmotionPredictionResultsObject->HappinessValue = $data["values"][EmotionType::Happiness];

                if(isset($data["values"][EmotionType::Affection]))
                    $EmotionPredictionResultsObject->AffectionValue = $data["values"][EmotionType::Affection];

                if(isset($data["values"][EmotionType::Sadness]))
                    $EmotionPredictionResultsObject->SadnessValue = $data["values"][EmotionType::Sadness];

                if(isset($data["values"][EmotionType::Anger]))
                    $EmotionPredictionResultsObject->AngerValue = $data["values"][EmotionType::Anger];
            }


            return $EmotionPredictionResultsObject;
        }

        /**
         * Constructs object from ServerInterface results
         *
         * @param array $data
         * @return EmotionPredictionResults
         */
        public static function fromResults(array $data): EmotionPredictionResults
        {
            $EmotionPredictionResultsObject = new EmotionPredictionResults();

            if(isset($data["neutral"]))
                $EmotionPredictionResultsObject->NeutralValue = (float)$data["neutral"];

            if(isset($data["happiness"]))
                $EmotionPredictionResultsObject->HappinessValue = (float)$data["happiness"];

            if(isset($data["affection"]))
                $EmotionPredictionResultsObject->AffectionValue = (float)$data["affection"];

            if(isset($data["sadness"]))
                $EmotionPredictionResultsObject->SadnessValue = (float)$data["sadness"];

            if(isset($data["anger"]))
                $EmotionPredictionResultsObject->AngerValue = (float)$data["anger"];

            $EmotionPredictionResultsObject->updateTopValue();

            return $EmotionPredictionResultsObject;
        }
    }