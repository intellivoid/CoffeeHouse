<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Abstracts\GeneralizedClassificationSearchMethod;
    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\GeneralizedClassificationNotFoundException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Objects\GeneralizedClassification;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class GeneralizedClassificationManager
     * @package CoffeeHouse\Managers
     */
    class GeneralizedClassificationManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * GeneralizedClassificationManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Creates a new generalized classification object in the database
         *
         * @param int $size
         * @return GeneralizedClassification
         * @throws DatabaseException
         * @throws GeneralizedClassificationNotFoundException
         * @throws InvalidSearchMethodException
         */
        public function create(int $size): GeneralizedClassification
        {
            $data = $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode(array()));
            $results = (float)0;
            $size = (int)$size;
            $current_pointer = (int)0;
            $last_updated = (int)time();
            $created = $last_updated;
            $public_id = Hashing::generalizedClassificationPublicId($created, $size);
            $public_id = $this->coffeeHouse->getDatabase()->real_escape_string($public_id);

            $Query = QueryBuilder::insert_into('generalized_classification', array(
                'public_id' => $public_id,
                'data' => $data,
                'results' => $results,
                'size' => $size,
                'current_pointer' => $current_pointer,
                'last_updated' => $last_updated,
                'created' => $created
            ));

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);
            if($QueryResults)
            {
                return($this->get(GeneralizedClassificationSearchMethod::byPublicID, $public_id));
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Returns an existing Generalized Classification row from the database
         *
         * @param string $search_method
         * @param string $value
         * @return GeneralizedClassification
         * @throws DatabaseException
         * @throws GeneralizedClassificationNotFoundException
         * @throws InvalidSearchMethodException
         */
        public function get(string $search_method, string $value): GeneralizedClassification
        {
            switch($search_method)
            {
                case GeneralizedClassificationSearchMethod::byID:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case GeneralizedClassificationSearchMethod::byPublicID:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = $this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("generalized_classification", array(
                'id',
                'public_id',
                'data',
                'results',
                'size',
                'current_pointer',
                'last_updated',
                'created'
            ), $search_method, $value);

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new GeneralizedClassificationNotFoundException();
                }
                else
                {
                    $Row['data'] = ZiProto::decode($Row['data']);
                    return(GeneralizedClassification::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }

        }
    }