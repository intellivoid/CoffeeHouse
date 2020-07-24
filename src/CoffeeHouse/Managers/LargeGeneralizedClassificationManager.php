<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Abstracts\GeneralizedClassificationSearchMethod;
    use CoffeeHouse\Abstracts\LargeGeneralizedClassificationSearchMethod;
    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Objects\Datums\LargeGeneralizationDatum;
    use CoffeeHouse\Objects\LargeGeneralization;
    use CoffeeHouse\Objects\Results\LargeClassificationResults;
    use msqg\Abstracts\SortBy;
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
         * Adds a new large generalization row into the database
         *
         * @param LargeGeneralizationDatum[] $largeGeneralizationData
         * @param string|null $generalization_public_id
         * @param int $limit
         * @return LargeClassificationResults
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         */
        public function add(array $largeGeneralizationData, string $generalization_public_id=null, int $limit=100): LargeClassificationResults
        {
            $LargeGeneralizationObject = new LargeGeneralization();

            foreach($largeGeneralizationData as $generalizationDatum)
            {
                $LargeGeneralizationObject->add($generalizationDatum->Label, $generalizationDatum->Probability);
            }

            $LargeGeneralizationObject->Created = (int)time();
            $LargeGeneralizationData = $LargeGeneralizationObject->toArray()["data"];

            if($generalization_public_id == null)
            {
                $LargeGeneralizationObject->PublicID = Hashing::largeGeneralizationPublicId($LargeGeneralizationObject);
            }
            else
            {
                $LargeGeneralizationObject->PublicID = $generalization_public_id;
            }

            $Query = QueryBuilder::insert_into("large_generalization", array(
                "public_id" => $this->coffeeHouse->getDatabase()->real_escape_string($LargeGeneralizationObject->PublicID),
                "top_label" => $this->coffeeHouse->getDatabase()->real_escape_string($LargeGeneralizationObject->TopLabel),
                "top_probability" => $this->coffeeHouse->getDatabase()->real_escape_string($LargeGeneralizationObject->TopProbability),
                "data" => $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode($LargeGeneralizationData)),
                "created" => (int)$LargeGeneralizationObject->Created
            ));

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);
            if($QueryResults)
            {
                if($generalization_public_id == null)
                {
                    return($this->get(GeneralizedClassificationSearchMethod::byPublicID, $LargeGeneralizationObject->PublicID, $limit));
                }

                return($this->get(GeneralizedClassificationSearchMethod::byPublicID, $generalization_public_id, $limit));
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
         * @param int $limit
         * @return LargeClassificationResults
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         * @noinspection PhpUnused
         */
        public function get(string $search_method, string $value, int $limit=100): LargeClassificationResults
        {
            switch($search_method)
            {
                case LargeGeneralizedClassificationSearchMethod::byPublicId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = $this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                case LargeGeneralizedClassificationSearchMethod::byId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("large_generalization", array(
                "id",
                "public_id",
                "top_label",
                "top_probability",
                "data",
                "created"
            ), $search_method, $value, "created", SortBy::descending, $limit);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                if($QueryResults->num_rows == 0)
                {
                    throw new NoResultsFoundException();
                }

                $large_classification_results = new LargeClassificationResults();

                foreach($QueryResults->fetch_array(MYSQLI_ASSOC) as $row)
                {
                    $row["data"] = ZiProto::decode($row["data"]);
                    $large_generalization = LargeGeneralization::fromArray($row);
                    $large_classification_results->LargeGeneralizations[] = $large_generalization;
                }

                $large_classification_results->combineProbabilities();
                $large_classification_results->updateTopK();

                return $large_classification_results;
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }
    }