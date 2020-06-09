<?php


    namespace CoffeeHouse\Objects\Results;

    use ZiProto\Packet;

    /**
     * Class SpamPredictionResults
     * @package CoffeeHouse\Objects\Results
     */
    class SpamPredictionResults
    {
        /**
         * The prediction percentage of the content being spam
         *
         * @var float
         */
        public $SpamPrediction;

        /**
         * The prediction percentage of the content not being spam
         *
         * @var float
         */
        public $HamPrediction;

        /**
         * Returns true if the results predict that the results are spam
         *
         * @return bool
         */
        public function isSpam(): bool
        {
            if($this->SpamPrediction > $this->HamPrediction)
            {
                return true;
            }

            return false;
        }

        /**
         * Returns the array structure of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'spam' => $this->SpamPrediction,
                'ham' => $this->HamPrediction
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SpamPredictionResults
         */
        public static function fromArray(array $data): SpamPredictionResults
        {
            $SpamPredictionResultsObject = new SpamPredictionResults();

            if(isset($data['spam']))
            {
                $SpamPredictionResultsObject->SpamPrediction = (float)$data['spam'];
            }

            if(isset($data['ham']))
            {
                $SpamPredictionResultsObject->HamPrediction = (float)$data['ham'];
            }

            return $SpamPredictionResultsObject;
        }
    }