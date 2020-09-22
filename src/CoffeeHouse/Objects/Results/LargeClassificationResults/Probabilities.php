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
         * The current pointer
         *
         * @var int
         */
        public $CurrentPointer;

        /**
         * The max amount of probabilities this object can store
         *
         * @var int
         */
        public $MaxProbabilities;

        /**
         * The summary of the probabilities
         *
         * @var float|int
         */
        public $CalculatedProbability;

        /**
         * Probabilities constructor.
         */
        public function __construct()
        {
            $this->Probabilities = array();
            $this->CalculatedProbability = 0;
            $this->CurrentPointer = 0;
            $this->MaxProbabilities = 50;
        }

        /**
         * Adds a new entry to the probability sum
         *
         * @param float $probability
         * @return float
         */
        public function add(float $probability): float
        {
            if($this->CurrentPointer > $this->MaxProbabilities)
            {
                $this->CurrentPointer = 0;
            }

            $this->Probabilities[(int)$this->CurrentPointer] = $probability;
            $this->CurrentPointer += 1;
            return $this->calculateGeneralProbability(true);
        }

        /**
         * Calculates the general probability, this method can update the property
         *
         * @param bool $update_property
         * @return float
         */
        public function calculateGeneralProbability(bool $update_property=true): float
        {
            $calculation = 0;

            foreach($this->Probabilities as $probability)
            {
                $calculation += $probability;
            }

            $resulted_calculation = ($calculation / count($this->Probabilities));

            if($update_property)
            {
                $this->CalculatedProbability = $resulted_calculation;
            }

            return $resulted_calculation;
        }

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                0x000 => $this->Label,
                0x001 => $this->Probabilities,
                0x002 => (int)$this->CurrentPointer,
                0x003 => (int)$this->MaxProbabilities,
                0x004 => $this->CalculatedProbability
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

            if(isset($data[0x000]))
            {
                $ProbabilitiesObject->Label = $data[0x000];
            }

            if(isset($data[0x001]))
            {
                $ProbabilitiesObject->Probabilities = $data[0x001];
            }

            if(isset($data[0x002]))
            {
                $ProbabilitiesObject->CurrentPointer = (int)$data[0x002];
            }

            if(isset($data[0x003]))
            {
                $ProbabilitiesObject->MaxProbabilities = (int)$data[0x003];
            }

            if(isset($data[0x004]))
            {
                $ProbabilitiesObject->CalculatedProbability = (float)$data[0x004];
            }

            return $ProbabilitiesObject;
        }
    }