<?php


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Objects\Results\EmotionPredictionSentencesResults\EmotionPredictionSentence;
    use CoffeeHouse\Objects\Results\SpamPredictionSentencesResults\SpamPredictionSentence;

    /**
     * Class SpamPredictionSentencesResults
     * @package CoffeeHouse\Objects\Results
     */
    class SpamPredictionSentencesResults
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
         * @var SpamPredictionResults|null
         */
        public ?SpamPredictionResults $SpamPrediction;

        /**
         * The spam prediction sentences
         *
         * @var SpamPredictionSentence[]|null
         */
        public ?array $SpamPredictionSentences;

        /**
         * EmotionPredictionSentencesResults constructor.
         */
        public function __construct()
        {
            $this->Text = null;
            $this->SpamPrediction = null;
            $this->SpamPredictionSentences = [];
        }

        /**
         * Calculates the current combined sentimental value
         *
         * @return SpamPredictionResults
         * @noinspection DuplicatedCode
         */
        public function calculateCombinedPredictions(): SpamPredictionResults
        {
            $spamPredictionResults = new SpamPredictionResults();

            $CombinedPredictions = [
                "spam" => (float)0,
                "ham" => (float)0
            ];

            $CombinedPredictionsCount = 0;

            foreach($this->SpamPredictionSentences as $spamPredictionSentence)
            {
                $CombinedPredictions["spam"] += $spamPredictionSentence->SpamPredictionResults->SpamPrediction;
                $CombinedPredictions["ham"] += $spamPredictionSentence->SpamPredictionResults->HamPrediction;
                $CombinedPredictionsCount += 1;
            }

            foreach($CombinedPredictions as $prediction_name => $prediction_value)
            {
                $CombinedPredictions[$prediction_name] =
                    ($CombinedPredictions[$prediction_name] / $CombinedPredictionsCount);
            }

            $spamPredictionResults->GeneralizedHam = (float)$CombinedPredictions["ham"];
            $spamPredictionResults->GeneralizedSpam = (float)$CombinedPredictions["spam"];

            $this->SpamPrediction = $spamPredictionResults;

            return $spamPredictionResults;
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $sentences = [];

            foreach($this->SpamPredictionSentences as $emotionPredictionSentence)
                $sentences = $emotionPredictionSentence->toArray();

            return [
                "text" => $this->Text,
                "spam_prediction" => $this->SpamPrediction->toArray(),
                "sentences" => $sentences
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SpamPredictionSentencesResults
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): SpamPredictionSentencesResults
        {
            $SpamPredictionSentencesResultsObject = new SpamPredictionSentencesResults();

            if(isset($data["text"]))
                $SpamPredictionSentencesResultsObject->Text = $data["text"];

            if(isset($data["sentences"]))
            {
                $SpamPredictionSentencesResultsObject->EmotionPredictionSentences = [];

                foreach($data["sentences"] as $datum)
                    $SpamPredictionSentencesResultsObject->EmotionPredictionSentences[] = EmotionPredictionSentence::fromArray($datum);
            }

            if(isset($data["spam_prediction"]))
            {
                $SpamPredictionSentencesResultsObject->SpamPrediction = SpamPredictionResults::fromArray($data["spam_prediction"]);
            }
            else
            {
                $SpamPredictionSentencesResultsObject->calculateCombinedPredictions();
            }


            return $SpamPredictionSentencesResultsObject;
        }
    }