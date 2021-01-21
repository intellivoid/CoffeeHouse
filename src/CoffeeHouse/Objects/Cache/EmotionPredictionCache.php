<?php


    namespace CoffeeHouse\Objects\Cache;

    use CoffeeHouse\Abstracts\EmotionType;
    use CoffeeHouse\Objects\Results\EmotionPredictionResults;

    /**
     * Class EmotionPredictionCache
     * @package CoffeeHouse\Objects\Cache
     */
    class EmotionPredictionCache
    {
        /**
         * The Unique Internal Database ID for this record
         *
         * @var int|null
         */
        public ?int $ID;

        /**
         * The Unique Hash that represents the input that represents these predictions
         *
         * @var string|null
         */
        public ?string $Hash;

        /**
         * The predictions
         *
         * @var array|null
         */
        public ?array $Predictions;

        /**
         * The top prediction value from the predictions
         *
         * @var float|null
         */
        public ?float $TopPrediction;

        /**
         * The top emotion from the predictions
         *
         * @var string|null
         */
        public ?string $TopEmotion;

        /**d
         * The Unix Timestamp for when this record was last updated
         *
         * @var int|null
         */
        public ?int $LastUpdated;

        /**
         * The Unix Timestamp for when this record was created
         *
         * @var int|null
         */
        public ?int $Created;

        /**
         * Constructs object from an array
         *
         * @param array $data
         * @return EmotionPredictionCache
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function fromArray(array $data): EmotionPredictionCache
        {
            $EmotionPredictionCacheObject = new EmotionPredictionCache();

            if(isset($data["id"]))
                $EmotionPredictionCacheObject->ID = (int)$data["id"];

            if(isset($data["hash"]))
                $EmotionPredictionCacheObject->Hash = $data["hash"];

            if(isset($data["predictions"]))
                $EmotionPredictionCacheObject->Predictions = $data["predictions"];

            if(isset($data["top_prediction"]))
                $EmotionPredictionCacheObject->TopPrediction = (float)$data["top_prediction"];

            if(isset($data["top_emotion"]))
                $EmotionPredictionCacheObject->TopEmotion = (string)$data["top_emotion"];

            if(isset($data["last_updated"]))
                $EmotionPredictionCacheObject->LastUpdated = (int)$data["last_updated"];

            if(isset($data["created"]))
                $EmotionPredictionCacheObject->Created = (int)$data["created"];

            return $EmotionPredictionCacheObject;
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
                "id" => $this->ID,
                "hash" => $this->Hash,
                "predictions" => $this->Predictions,
                "top_prediction" => $this->TopPrediction,
                "top_emotion" => $this->TopEmotion,
                "last_updated" => $this->LastUpdated,
                "created" => $this->Created
            ];
        }

        /**
         * Creates a results object from this cache object
         *
         * @return EmotionPredictionResults
         */
        public function toResults(): EmotionPredictionResults
        {
            $EmotionPredictionResultsObject = new EmotionPredictionResults();

            $EmotionPredictionResultsObject->TopEmotion = $this->TopEmotion;
            $EmotionPredictionResultsObject->TopValue = $this->TopPrediction;
            $EmotionPredictionResultsObject->NeutralValue = $this->Predictions[EmotionType::Neutral];
            $EmotionPredictionResultsObject->HappinessValue = $this->Predictions[EmotionType::Happiness];
            $EmotionPredictionResultsObject->AngerValue = $this->Predictions[EmotionType::Anger];
            $EmotionPredictionResultsObject->AffectionValue = $this->Predictions[EmotionType::Affection];
            $EmotionPredictionResultsObject->SadnessValue = $this->Predictions[EmotionType::Sadness];

            return $EmotionPredictionResultsObject;
        }
    }