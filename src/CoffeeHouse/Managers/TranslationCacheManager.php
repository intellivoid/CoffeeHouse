<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Objects\Results\TranslationResults;
    use msqg\QueryBuilder;

    /**
     * Class TranslationCacheManager
     * @package CoffeeHouse\Managers
     */
    class TranslationCacheManager
    {

        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * SpamPredictionCacheManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Registers the translation cache into the database
         *
         * @param TranslationResults $translationResults
         * @return bool
         * @throws DatabaseException
         */
        public function registerCache(TranslationResults $translationResults): bool
        {
            $public_id = Hashing::translateCachePublicId(
                $translationResults->Source,
                $translationResults->Target,
                $translationResults->Input
            );

            $public_id = $this->coffeeHouse->getDatabase()->real_escape_string($public_id);
            $source = $this->coffeeHouse->getDatabase()->real_escape_string($translationResults->Source);
            $target = $this->coffeeHouse->getDatabase()->real_escape_string($translationResults->Target);
            $processing_engine = $this->coffeeHouse->getDatabase()->real_escape_string($translationResults->ProcessingEngine);
            $input = $this->coffeeHouse->getDatabase()->real_escape_string($translationResults->Input);
            $output = $this->coffeeHouse->getDatabase()->real_escape_string($translationResults->Output);
            $created_timestamp = (int)time();
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::insert_into("translate_cache", array(
                "public_id" => $public_id,
                "source" => $source,
                "target" => $target,
                "processing_engine" => $processing_engine,
                "input" => $input,
                "output" => $output,
                "created_timestamp" => $created_timestamp,
                "last_updated_timestamp" => $last_updated_timestamp
            ));

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

    }