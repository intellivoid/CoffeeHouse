<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
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
        }

        public function getGeneralizedClassification(s)
    }