<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Objects\Datums\LargeGeneralizationDatum;
    use CoffeeHouse\Objects\LargeGeneralization;

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
         * @param LargeGeneralizationDatum[] $largeGeneralizationData
         * @param string|null $generalization_public_id
         * @return LargeGeneralization
         */
        public function add(array $largeGeneralizationData, string $generalization_public_id=null): LargeGeneralization
        {
            $LargeGeneralizationObject = new LargeGeneralization();

            foreach($largeGeneralizationData as $generalizationDatum)
            {
                $LargeGeneralizationObject->add($generalizationDatum->Label, $generalizationDatum->Probability);
            }
            $LargeGeneralizationObject->Created = (int)time();

        }
    }