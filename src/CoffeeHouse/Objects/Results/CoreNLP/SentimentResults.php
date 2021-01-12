<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP;

    use CoffeeHouse\Objects\ProcessedNLP\Sentence;
    use CoffeeHouse\Objects\ProcessedNLP\Sentiment;
    use CoffeeHouse\Objects\Results\CoreNLP\SentimentResults\SentimentSentence;

    /**
     * Class SentimentResults
     * @package CoffeeHouse\Objects\Results\CoreNLP
     */
    class SentimentResults
    {
        /**
         * The text that was processed
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * The overall predictions of all the sentiments combined
         *
         * @var Sentiment|null
         */
        public Sentiment $Sentiment;

        /**
         * The array of sentences with their sentimental analaysis
         *
         * @var SentimentSentence[]|null
         */
        public ?array $SentimentSentences;

        /**
         * Calculates the current combined sentimental value
         *
         * @return Sentiment
         */
        public function calculateCombinedSentiment(): Sentiment
        {
            $SentimentObject = new Sentiment();

            $CombinedPredictions = [];
            $CombinedPredictionCount = [];

            foreach($this->SentimentSentences as $sentimentSentence)
            {
                foreach($sentimentSentence->Sentiment->Predictions as $prediction_name => $prediction_value)
                {
                    if(isset($CombinedPredictions[$prediction_name]) == false)
                    {
                        $CombinedPredictions[$prediction_name] = (float)0;
                        $CombinedPredictionCount[$prediction_name] = (int)0;
                    }

                    $CombinedPredictions[$prediction_name] += $prediction_value;
                    $CombinedPredictionCount[$prediction_name] += 1;
                }
            }

            foreach($CombinedPredictions as $prediction_name => $prediction_value)
            {
                $CombinedPredictions[$prediction_name] =
                    ($CombinedPredictions[$prediction_name] / $CombinedPredictionCount[$prediction_name]);
            }

            $CurrentPredictionName = null;
            $CurrentPredictionValue = null;

            foreach($CombinedPredictions as $prediction_name => $prediction_value)
            {
                // First iteration
                if($CurrentPredictionName == null && $CurrentPredictionValue == null)
                {
                    $CurrentPredictionName = $prediction_name;
                    $CurrentPredictionValue = $prediction_value;
                }
                else
                {
                    if($prediction_value > $CurrentPredictionValue)
                    {
                        $CurrentPredictionName = $prediction_name;
                        $CurrentPredictionValue = $prediction_value;
                    }
                }
            }

            $SentimentObject->Predictions = $CombinedPredictions;
            $SentimentObject->TopSentiment = $CurrentPredictionName;
            $SentimentObject->TopPrediction = $CurrentPredictionValue;

            $this->Sentiment = $SentimentObject;

            return $SentimentObject;
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $sentiment_sentences = [];

            foreach($this->SentimentSentences as $sentence)
                $sentiment_sentences[] = $sentence->toArray();

            if($this->Sentiment == null)
                $this->calculateCombinedSentiment();

            return [
                "text" => $this->Text,
                "sentiment" => $this->Sentiment->toArray(),
                "sentiment_sentences" => $sentiment_sentences,
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SentimentResults
         */
        public static function fromArray(array $data): SentimentResults
        {
            $SentimentResultsObject = new SentimentResults();

            if(isset($data["text"]))
                $SentimentResultsObject->Text = $data["text"];

            if(isset($data["sentiment_sentences"]))
            {
                $SentimentResultsObject->SentimentSentences = [];

                foreach($data["sentiment_sentences"] as $sentiment_sentence)
                    $SentimentResultsObject->SentimentSentences[] = SentimentSentence::fromArray($sentiment_sentence);
            }

            if(isset($data["sentiment"]))
            {
                $SentimentResultsObject->Sentiment = Sentiment::fromArray($data["sentiment"]);
            }
            else
            {
                if(isset($data["sentiment_sentences"]))
                    $SentimentResultsObject->calculateCombinedSentiment();
            }


            return $SentimentResultsObject;
        }

        /**
         * Constructs object from sentence
         *
         * @param string $text
         * @param Sentence[] $data
         * @return SentimentResults
         */
        public static function fromSentences(string $text, array $data): SentimentResults
        {
            $SentimentResultsObject = new SentimentResults();

            $SentimentResultsObject->Text = $text;
            $SentimentResultsObject->SentimentSentences = [];

            foreach($data as $sentence)
            {
                $SentenceObject = new SentimentSentence();

                $SentenceObject->Text = $sentence->Text;
                $SentenceObject->OffsetBegin = $sentence->OffsetBegin;
                $SentenceObject->OffsetEnd = $sentence->OffsetEnd;
                $SentenceObject->Sentiment = $sentence->Sentiment;

                $SentimentResultsObject->SentimentSentences[] = $SentenceObject;
            }

            $SentimentResultsObject->calculateCombinedSentiment();

            return $SentimentResultsObject;
        }
    }