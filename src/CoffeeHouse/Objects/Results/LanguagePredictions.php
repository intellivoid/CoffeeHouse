<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Objects\Results;

    /**
     * Class LanguagePredictions
     * @package CoffeeHouse\Objects\Results
     */
    class LanguagePredictions
    {
        /**
         * The language for the prediction
         *
         * @var string
         */
        public $Language;

        /**
         * Array of probabilities
         *
         * @var float[]|int[]
         */
        public $Probabilities;

        /**
         * Returns an array which represents this object
         *
         * @param bool $bytes
         * @return array
         */
        public function toArray(bool $bytes=false): array
        {
            if($bytes)
            {
                return array($this->Language, $this->Probabilities);
            }

            return array(
                "language" => $this->Language,
                "probabilities" => $this->Probabilities
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @param bool $bytes
         * @return LanguagePredictions
         */
        public static function fromArray(array $data, bool $bytes=false): LanguagePredictions
        {
            $LanguagePredictions = new LanguagePredictions();

            if($bytes)
            {
                $LanguagePredictions->Language = $data[0];
                $LanguagePredictions->Probabilities = $data[1];
            }
            else
            {
                if(isset($data["language"]))
                {
                    $LanguagePredictions->Language = $data["language"];
                }

                if(isset($data["probability"]))
                {
                    $LanguagePredictions->Probabilities = $data["probability"];
                }
            }

            return $LanguagePredictions;
        }
    }