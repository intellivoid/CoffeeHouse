<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Objects\GeneralizedClassification;
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

        public function createNewGeneralizedClassification(int $size): GeneralizedClassification
        {
            $data = $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode(array()));
            $results = (float)0;
            $size = (int)$size;
            $current_pointer = (int)0;
            $last_updated = (int)time();
            $created = $last_updated;
            $public_id = Hashing::generalizedClassificationPublicId($created, $size);
        }
    }