<?php


    namespace CoffeeHouse\Managers;

    use CoffeeHouse\Abstracts\LargeGeneralizedClassificationSearchMethod;
    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Objects\LargeGeneralization;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class LargeGeneralizedClassificationManager
     * @package CoffeeHouse\Manager
     */
    class LargeGeneralizedClassificationManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * LargeGeneralizedClassificationManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Creates a new large generalization object in the database
         *
         * @param int $max_probabilities
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         */
        public function create(int $max_probabilities=50): LargeGeneralization
        {
            $PublicID = Hashing::largeGeneralizationPublicId();

            $Query = QueryBuilder::insert_into("large_generalization", array(
                "public_id" => $this->coffeeHouse->getDatabase()->real_escape_string($PublicID),
                "max_probabilities" => (int)$max_probabilities,
                "probabilities" => $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode(array())),
                "top_label" => null,
                "top_probability" => (float)0,
                "created_timestamp" => (int)time(),
                "last_updated_timestamp" => (int)time()
            ));

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);
            if($QueryResults)
            {
                return($this->get(LargeGeneralizedClassificationSearchMethod::byPublicID, $PublicID));
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }

        }

        /**
         * Returns Large Classification Results from the database
         *
         * @param string $search_method
         * @param string $value
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         * @noinspection PhpUnused
         */
        public function get(string $search_method, string $value): LargeGeneralization
        {
            switch($search_method)
            {
                case LargeGeneralizedClassificationSearchMethod::byPublicID:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = $this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                case LargeGeneralizedClassificationSearchMethod::byID:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("large_generalization", array(
                "id",
                "public_id",
                "max_probabilities",
                "probabilities",
                "top_label",
                "top_probability",
                "created_timestamp",
                "last_updated_timestamp"
            ), $search_method, $value);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                if($QueryResults->num_rows == 0)
                {
                    throw new NoResultsFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if($Row == null)
                {
                    throw new NoResultsFoundException();
                }

                $Row["probabilities"] = ZiProto::decode($Row["probabilities"]);
                return LargeGeneralization::fromArray($Row);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates an existing large generalization object in the database
         *
         * @param LargeGeneralization $largeGeneralization
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         */
        public function update(LargeGeneralization $largeGeneralization)
        {
            $this->get(LargeGeneralizedClassificationSearchMethod::byID, $largeGeneralization->ID);
            $array_results = $largeGeneralization->toArray();

            $max_probabilities = (int)$largeGeneralization->MaxProbabilitiesSize;
            $probabilities = $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode($array_results["probabilities"]));
            $top_label = $largeGeneralization->TopLabel;
            $top_probability = (float)$largeGeneralization->TopProbability;
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::update("large_generalization", array(
                "max_probabilities" => $max_probabilities,
                "probabilities" => $probabilities,
                "top_label" => $top_label,
                "top_probability" => $top_probability,
                "last_updated_timestamp" => $last_updated_timestamp
            ), "id", (int)$largeGeneralization->ID);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                return(True);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }

        }
    }