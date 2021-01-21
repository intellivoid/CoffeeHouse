<?php


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Abstracts\EmotionType;
    use CoffeeHouse\Objects\Results\EmotionPredictionSentencesResults\EmotionPredictionSentence;

    /**
     * Class EmotionPredictionSentencesResults
     * @package CoffeeHouse\Objects\Results
     */
    class EmotionPredictionSentencesResults
    {
        /**
         * The text that was processed
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * An overall prediction of all the sentences combined
         *
         * @var EmotionPredictionResults|null
         */
        public ?EmotionPredictionResults $EmotionPrediction;

        /**
         * @var EmotionPredictionSentence[]|null
         */
        public ?array $EmotionPredictionSentences;

        /**
         * EmotionPredictionSentencesResults constructor.
         */
        public function __construct()
        {
            $this->Text = null;
            $this->EmotionPrediction = null;
            $this->EmotionPredictionSentences = [];
        }

        /**
         * Calculates the current combined sentimental value
         *
         * @return EmotionPredictionResults
         * @noinspection DuplicatedCode
         */
        public function calculateCombinedSentiment(): EmotionPredictionResults
        {
            $emotionPredictionResults = new EmotionPredictionResults();

            $CombinedPredictions = [];
            $CombinedPredictionCount = [];

            foreach($this->EmotionPredictionSentences as $emotionPredictionSentence)
            {
                foreach($emotionPredictionSentence->EmotionPredictionResults->valuesToArray() as $emotion => $prediction_value)
                {
                    if(isset($CombinedPredictions[$emotion]) == false)
                    {
                        $CombinedPredictions[$emotion] = (float)0;
                        $CombinedPredictionCount[$emotion] = (int)0;
                    }

                    $CombinedPredictions[$emotion] += $prediction_value;
                    $CombinedPredictionCount[$emotion] += 1;
                }
            }

            foreach($CombinedPredictions as $prediction_name => $prediction_value)
            {
                $CombinedPredictions[$prediction_name] =
                    ($CombinedPredictions[$prediction_name] / $CombinedPredictionCount[$prediction_name]);
            }

            $emotionPredictionResults->SadnessValue = (float)$CombinedPredictions[EmotionType::Sadness];
            $emotionPredictionResults->AngerValue = (float)$CombinedPredictions[EmotionType::Anger];
            $emotionPredictionResults->HappinessValue = (float)$CombinedPredictions[EmotionType::Happiness];
            $emotionPredictionResults->AffectionValue = (float)$CombinedPredictions[EmotionType::Affection];
            $emotionPredictionResults->NeutralValue = (float)$CombinedPredictions[EmotionType::Neutral];
            $emotionPredictionResults->updateTopValue();

            $this->EmotionPrediction = $emotionPredictionResults;

            return $emotionPredictionResults;
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $sentences = [];

            foreach($this->EmotionPredictionSentences as $emotionPredictionSentence)
                $sentences = $emotionPredictionSentence->toArray();

            return [
                "text" => $this->Text,
                "emotion_prediction" => $this->EmotionPrediction->toArray(),
                "sentences" => $sentences
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return EmotionPredictionSentencesResults
         */
        public static function fromArray(array $data): EmotionPredictionSentencesResults
        {
            $EmotionPredictionSentencesResultsObject = new EmotionPredictionSentencesResults();

            if(isset($data["text"]))
                $EmotionPredictionSentencesResultsObject->Text = $data["text"];

            if(isset($data["sentences"]))
            {
                $EmotionPredictionSentencesResultsObject->EmotionPredictionSentences = [];

                foreach($data["sentences"] as $datum)
                    $EmotionPredictionSentencesResultsObject->EmotionPredictionSentences[] = EmotionPredictionSentence::fromArray($datum);
            }

            if(isset($data["emotion_prediction"]))
            {
                $EmotionPredictionSentencesResultsObject->EmotionPrediction = EmotionPredictionResults::fromArray($data["emotion_prediction"]);
            }
            else
            {
                $EmotionPredictionSentencesResultsObject->calculateCombinedSentiment();
            }


            return $EmotionPredictionSentencesResultsObject;
        }
    }