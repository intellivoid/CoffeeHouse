<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Objects\Results;

    /**
     * Class LanguagePredictionResults
     * @package CoffeeHouse\Objects\Results
     */
    class LanguagePredictionResults
    {
        /***
         * SHA256 Hash of the input
         *
         * @var string
         */
        public $Hash;

        /**
         * @var LanguagePrediction[]|null
         */
        public $DLTC_Results;

        /**
         * @var LanguagePrediction[]|null
         */
        public $CLD_Results;

        /**
         * @var LanguagePrediction[]|null
         */
        public $LD_Results;

        /**
         * Returns the array that represents this object
         *
         * @param bool $bytes
         * @return array
         */
        public function toArray(bool $bytes=false): array
        {
            $DLTC_Results = null;
            $CLD_Results = null;
            $LD_Results = null;

            if($this->DLTC_Results !== null)
            {
                $DLTC_Results = array();

                foreach($this->DLTC_Results as $result)
                {
                    $DLTC_Results[] = $result->toArray($bytes);
                }
            }

            if($this->CLD_Results !== null)
            {
                $CLD_Results = array();

                foreach($this->CLD_Results as $result)
                {
                    $CLD_Results[] = $result->toArray($bytes);
                }
            }

            if($this->LD_Results !== null)
            {
                $LD_Results = array();

                foreach($this->LD_Results as $result)
                {
                    $LD_Results[] = $result->toArray($bytes);
                }
            }

            return array(
                "hash" => $this->Hash,
                "dltc_results" => $DLTC_Results,
                "cld_results" => $CLD_Results,
                "ld_results" => $LD_Results
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @param bool $bytes
         * @return LanguagePredictionResults
         */
        public static function fromArray(array $data, bool $bytes=false): LanguagePredictionResults
        {
            $LanguagePredictionResultsObject = new LanguagePredictionResults();

            if(isset($data["hash"]))
            {
                $LanguagePredictionResultsObject->Hash = $data["hash"];
            }

            if(isset($data["dltc_results"]))
            {
                $LanguagePredictionResultsObject->DLTC_Results = array();

                foreach($data["dltc_results"] as $datum)
                {
                    $LanguagePredictionResultsObject->DLTC_Results = LanguagePrediction::fromArray($datum, $bytes);
                }
            }

            if(isset($data["cld_results"]))
            {
                $LanguagePredictionResultsObject->CLD_Results = array();

                foreach($data["cld_results"] as $datum)
                {
                    $LanguagePredictionResultsObject->CLD_Results[] = LanguagePrediction::fromArray($datum, $bytes);
                }
            }

            if(isset($data["ld_results"]))
            {
                $LanguagePredictionResultsObject->LD_Results = array();

                foreach($data["ld_results"] as $datum)
                {
                    $LanguagePredictionResultsObject->LD_Results[] = LanguagePrediction::fromArray($datum, $bytes);
                }
            }

            return $LanguagePredictionResultsObject;
        }
    }