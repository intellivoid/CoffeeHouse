<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Objects;

    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\ProbabilitySearchNoResultsFoundException;
    use CoffeeHouse\Objects\Results\LargeClassificationResults\Probabilities;

    /**
     * Class LargeClassificationResults
     * @package CoffeeHouse\Objects\Results
     */
    class LargeGeneralization
    {
        /**
         * Unique Internal Database ID for this record
         *
         * @var int
         */
        public $ID;

        /**
         * The Public ID of the generalization results
         *
         * @var string
         */
        public $PublicID;

        /**
         * The max amount of probabilities can be stored per label before
         * the first probability gets overwritten
         *
         * @var int
         */
        public $MaxProbabilitiesSize;

        /**
         * All the probabilities
         *
         * @var Probabilities[]
         */
        public $Probabilities;

        /**
         * @var string
         */
        public $TopLabel;

        /**
         * @var float|int
         */
        public $TopProbability;

        /**
         * @var int
         */
        public $CreatedTimestamp;

        /**
         * @var int
         */
        public $LastUpdatedTimestamp;

        /**
         * LargeGeneralization constructor.
         */
        public function __construct()
        {
            $this->Probabilities = array();
        }

        /**
         * Creates/Updates a probability in this large classification
         *
         * @param string $label
         * @param float $probability
         * @param bool $update
         * @return bool
         * @throws MalformedDataException
         */
        public function add(string $label, float $probability, bool $update=true): bool
        {
            try
            {
                $search_results = $this->findProbabilityByLabel($label);

                /** @var Probabilities $results_object */
                $results_object = $search_results["result"];
                $results_object->MaxProbabilities = $this->MaxProbabilitiesSize;
                $results_object->add($probability);

                $this->Probabilities[(int)$search_results["index"]] = $results_object;

                if($update)
                {
                    $this->sortProbabilities(true);
                }

                return true;
            }
            catch (ProbabilitySearchNoResultsFoundException $e)
            {
                unset($e);
            }

            $probabilities_object = new Probabilities();
            $probabilities_object->MaxProbabilities = $this->MaxProbabilitiesSize;
            $probabilities_object->Label = $label;
            $probabilities_object->add($probability);
            $this->Probabilities[] = $probabilities_object;

            if($update)
            {
                $this->sortProbabilities(true);
            }

            return true;
        }

        /**
         * Finds a probability object by label name
         *
         * @param string $label
         * @return array (index(int) ? result(Probabilities))
         * @throws MalformedDataException
         * @throws ProbabilitySearchNoResultsFoundException
         */
        public function findProbabilityByLabel(string $label): array
        {
            if(is_null($this->Probabilities))
            {
                throw new MalformedDataException("The array 'Probabilities' is null");
            }

            $current_index = 0;
            foreach($this->Probabilities as $probabilities)
            {
                if($probabilities->Label == $label)
                {
                    return array("index" => $current_index, "result" => $probabilities);
                }

                $current_index += 1;
            }

            throw new ProbabilitySearchNoResultsFoundException("No probabilities for the label '$label' was found");
        }

        /**
         * Sorts the probabilities from highest value to lowest value
         *
         * @param bool $update_top_k
         * @return array|Probabilities[]
         * @throws MalformedDataException
         */
        public function sortProbabilities(bool $update_top_k=true): array
        {
            $SortedResults = array();
            for ($i = 0; $i < count($this->Probabilities); $i++)
            {
                $LargestProbability = null;
                $CurrentSelection = null;

                foreach($this->Probabilities as $prediction)
                {
                    $prediction->calculateGeneralProbability(true);

                    if($prediction->CalculatedProbability == null)
                    {
                        continue;
                    }

                    if($prediction->Label == null)
                    {
                        continue;
                    }

                    if($prediction->Probabilities == null)
                    {
                        continue;
                    }

                    if(count($prediction->Probabilities) == 0)
                    {
                        continue;
                    }

                    if(isset($SortedResults[$prediction->Label]) == false)
                    {
                        if($prediction->CalculatedProbability > $LargestProbability)
                        {
                            $LargestProbability = $prediction->CalculatedProbability;
                            $CurrentSelection = $prediction;
                        }
                    }
                }

                $SortedResults[$CurrentSelection->Label] = $CurrentSelection;
            }

            $this->Probabilities = array();
            /** @var Probabilities $probability */
            foreach($SortedResults as $label_name => $probability)
            {
                $this->Probabilities[] = $probability;
            }

            if($update_top_k)
            {
                $this->updateTopK();
            }

            return $this->Probabilities;
        }

        /**
         * Updates the Top Results properties
         *
         * @return Probabilities
         * @throws MalformedDataException
         */
        public function updateTopK(): Probabilities
        {
            if($this->Probabilities == null)
            {
                throw new MalformedDataException("The property 'Probabilities' is null");
            }

            if($this->Probabilities[0] == null)
            {
                throw new MalformedDataException("The Probabilities results of the first index is null");
            }

            $this->TopProbability = $this->Probabilities[0]->CalculatedProbability;
            $this->TopLabel = $this->Probabilities[0]->Label;

            return $this->Probabilities[0];
        }


        /**
         * Returns an array which represents this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $probabilities_data = array();

            foreach($this->Probabilities as $probabilities)
            {
                $probabilities_data[] = $probabilities->toArray();
            }

            return array(
                "id" => (int)$this->ID,
                "public_id" => $this->PublicID,
                "max_probabilities_size" => $this->MaxProbabilitiesSize,
                "probabilities" => $probabilities_data,
                "top_label" => $this->TopLabel,
                "top_probability" => $this->TopProbability,
                "created_timestamp" => $this->CreatedTimestamp,
                "last_updated_timestamp" => $this->LastUpdatedTimestamp
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
            $LargeClassificationResultsObject = new LargeGeneralization();

            if(isset($data["id"]))
            {
                $LargeClassificationResultsObject->ID = $data["id"];
            }

            if(isset($data["public_id"]))
            {
                $LargeClassificationResultsObject->PublicID = $data["public_id"];
            }

            if(isset($data["max_probabilities"]))
            {
                $LargeClassificationResultsObject->MaxProbabilitiesSize = (int)$data["max_probabilities"];
            }

            if(isset($data["probabilities"]))
            {
                $LargeClassificationResultsObject->Probabilities = [];

                foreach($data["probabilities"] as $probability)
                {
                    $object = Probabilities::fromArray($probability);
                    $LargeClassificationResultsObject->Probabilities[] = $object;
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

            if(isset($data["created_timestamp"]))
            {
                $LargeClassificationResultsObject->CreatedTimestamp = (int)$data["created_timestamp"];
            }

            if(isset($data["last_updated_timestamp"]))
            {
                $LargeClassificationResultsObject->LastUpdatedTimestamp = (int)$data["last_updated_timestamp"];
            }

            return $LargeClassificationResultsObject;
        }
    }