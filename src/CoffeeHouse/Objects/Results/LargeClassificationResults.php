<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Objects\Results;


    use CoffeeHouse\Objects\LargeGeneralization;

    /**
     * Class LargeClassificationResults
     * @package CoffeeHouse\Objects\Results
     */
    class LargeClassificationResults
    {
        /**
         * @var LargeGeneralization[]
         */
        public $LargeGeneralizations;

        /**
         * @var string
         */
        public $TopLabel;

        /**
         * @var float|int
         */
        public $TopProbability;

        /**
         * LargeClassificationResults constructor.
         */
        public function __construct()
        {
            $this->LargeGeneralizations = array();
        }

        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $large_generalization_data = array();

            foreach($this->LargeGeneralizations as $largeGeneralization)
            {
                $large_generalization_data[] = $largeGeneralization->toArray();
            }

            return array(
                "large_generalizations" => $large_generalization_data,
                "top_label" => $this->TopLabel,
                "top_probability" => $this->TopProbability
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return LargeClassificationResults
         */
        public static function fromArray(array $data): LargeClassificationResults
        {
            $LargeClassificationResultsObject = new LargeClassificationResults();

            if(isset($data["large_generalizations"]))
            {
                foreach($data["large_generalizations"] as $datum)
                {
                    $LargeClassificationResultsObject[] = LargeGeneralization::fromArray($datum);
                }
            }

            if(isset($data["top_label"]))
            {
                $LargeClassificationResultsObject->TopLabel = $data["top_label"];
            }

            if(isset($data["top_probability"]))
            {
                $LargeClassificationResultsObject->TopProbability = (float)$data["top_probability"];
            }

            return $LargeClassificationResultsObject;
        }
    }