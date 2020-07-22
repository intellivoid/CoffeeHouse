<?php


    namespace CoffeeHouse\Objects\Results\LargeClassificationResults;

    /**
     * Class Probabilities
     * @package CoffeeHouse\Objects\Results\LargeClassificationResults
     */
    class Probabilities
    {
        /**
         * The label of the probability
         *
         * @var string
         */
        public $Label;

        /**
         * Array of probabilities
         *
         * @var float[]|int[]
         */
        public $Probabilities;

        /**
         * The summary of the probabilities
         *
         * @var float|int
         */
        public $CalculatedProbability;

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "label" => $this->Label,
                "probabilities" => $this->Probabilities,
                "calculated_probability" => $this->CalculatedProbability
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Probabilities
         */
        public static function fromArray(array $data): Probabilities
        {
            $ProbabilitiesObject = new Probabilities();

            if(isset($data["label"]))
            {
                $ProbabilitiesObject->Label = $data["label"];
            }

            if(isset($data["probabilities"]))
            {
                $ProbabilitiesObject->Probabilities = $data["probabilities"];
            }

            if(isset($data["calculated_probability"]))
            {
                $ProbabilitiesObject->CalculatedProbability = (float)$data["calculated_probability"];
            }

            return $ProbabilitiesObject;
        }
    }