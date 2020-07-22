<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Objects\Datums\LargeGeneralizationDatum;
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
         * Adds a new large generalization row into the database
         *
         * @param LargeGeneralizationDatum[] $largeGeneralizationData
         * @param string|null $generalization_public_id
         * @return LargeGeneralization
         * @throws DatabaseException
         */
        public function add(array $largeGeneralizationData, string $generalization_public_id=null): LargeGeneralization
        {
            $LargeGeneralizationObject = new LargeGeneralization();

            foreach($largeGeneralizationData as $generalizationDatum)
            {
                $LargeGeneralizationObject->add($generalizationDatum->Label, $generalizationDatum->Probability);
            }

            $LargeGeneralizationObject->Created = (int)time();
            $LargeGeneralizationObject->PublicID = Hashing::largeGeneralizationPublicId($LargeGeneralizationObject);
            $LargeGeneralizationData = $LargeGeneralizationObject->toArray()["data"];

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
                //return($this->get(GeneralizedClassificationSearchMethod::byPublicID, $public_id));
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * @param string $search_method
         * @param string $value
         * @param int $limit
         * @return array
         */
        public function get(string $search_method, string $value, int $limit): array
        {

        }
    }