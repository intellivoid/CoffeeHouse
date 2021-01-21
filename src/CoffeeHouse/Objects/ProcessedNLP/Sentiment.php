<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\ProcessedNLP;

    use CoffeeHouse\Classes\Utilities;

    /**
     * Class Sentiment
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class Sentiment
    {
        /**
         * The top sentimental value
         *
         * @var string|null
         */
        public $TopSentiment;

        /**
         * The top sentimental prediction value
         *
         * @var float|null
         */
        public $TopPrediction;

        /**
         * Array of prediction values
         *
         * @var array
         */
        public $Predictions;

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Sentiment
         */
        public static function fromArray(array $data): Sentiment
        {
            $SentimentObject = new Sentiment();

            if(isset($data["sentiment"]))
                $SentimentObject->TopSentiment = match (strtolower($data["sentiment"])) {
                    "verynegative" => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::VeryNegative,
                    "negative" => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::Negative,
                    "neutral" => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::Neutral,
                    "positive" => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::Positive,
                    "verypositive" => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::VeryPositive,
                    default => \CoffeeHouse\Abstracts\CoreNLP\Sentiment::Unknown,
                };

            if(isset($data["top_sentiment"]))
                $SentimentObject->TopSentiment = $data["top_sentiment"];

            if(isset($data["top_prediction"]))
            {
                $SentimentObject->TopPrediction = (float)$data["top_prediction"];
            }

            if(isset($data["sentimentDistribution"]))
            {
                $SentimentObject->Predictions = [];
                foreach($data["sentimentDistribution"] as $key => $datum)
                    $SentimentObject->Predictions[Utilities::sentimentValueToString($key)] = (float)$datum;

                if($SentimentObject->TopPrediction == null)
                {
                    if(isset($data["sentimentValue"]))
                        $SentimentObject->TopPrediction = $data["sentimentDistribution"][$data["sentimentValue"]];
                }
            }

            if(isset($data["predictions"]))
                $SentimentObject->Predictions = $data["predictions"];

            if(isset($data["predictions"]))
                $SentimentObject->Predictions = $data["predictions"];


            return $SentimentObject;
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
                "top_sentiment" => $this->TopSentiment,
                "top_prediction" => $this->TopPrediction,
                "predictions" => $this->Predictions
            ];
        }
    }