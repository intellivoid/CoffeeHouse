<?php


    namespace CoffeeHouse\Objects;


    use CoffeeHouse\Objects\Datums\LargeGeneralizationDatum;

    /**
     * Class LargeGeneralization
     * @package CoffeeHouse\Objects
     */
    class LargeGeneralization
    {
        /**
         * Unique Internal Database ID
         *
         * @var int
         */
        public $ID;

        /**
         * The unique Public ID that's not unique
         *
         * @var string
         */
        public $PublicID;

        /**
         * The label of the top result
         *
         * @var string
         */
        public $TopLabel;

        /**
         * The probability of the top result
         *
         * @var float|int
         */
        public $TopProbability;

        /**
         * @var LargeGeneralizationDatum[]
         */
        public $Data;

        /**
         * The data limit for the large generalization model
         *
         * @var int
         */
        public $Limit;

        /**
         * Unix Timestamp of when this row was created
         *
         * @var int
         */
        public $Created;

        /**
         * Adds a new entry to the the generalization model
         *
         * @param string $label
         * @param float $probability
         * @return bool
         */
        public function add(string $label, float $probability): bool
        {
            if(count($this->Data) == $this->Limit)
            {
                array_shift($this->Data);
            }

            $datum = new LargeGeneralizationDatum();
            $datum->Label = $label;
            $datum->Probability = (float)$probability;

            $this->Data[] = $datum;

            return True;
        }

        /**
         * Returns an array that represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                "id" => (int)$this->ID,
                "public_id" => $this->PublicID,
                "top_label" => $this->TopLabel,
                "top_probability" => (float)$this->TopProbability,
                "data" => $this->Data,
                "created" => (int)$this->Created
            );
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return LargeGeneralization
         */
        public static function fromArray(array $data): LargeGeneralization
        {
            $LargeGeneralizationObject = new LargeGeneralization();

            if(isset($data["id"]))
            {
                $LargeGeneralizationObject->ID = (int)$data["id"];
            }

            if(isset($data["public_id"]))
            {
                $LargeGeneralizationObject->PublicID = $data["public_id"];
            }

            if(isset($data["top_label"]))
            {
                $LargeGeneralizationObject->TopLabel = $data["top_label"];
            }

            if(isset($data["top_probability"]))
            {
                $LargeGeneralizationObject->TopProbability = (float)$data["top_probability"];
            }

            if(isset($data["data"]))
            {
                $LargeGeneralizationObject->Data = $data["data"];
            }

            if(isset($data["created"]))
            {
                $LargeGeneralizationObject->Created = (int)$data["created"];
            }

            return $LargeGeneralizationObject;
        }
    }