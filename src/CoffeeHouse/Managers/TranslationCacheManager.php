<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Abstracts\TranslationCacheSearchMethod;
    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\TranslationCacheNotFoundException;
    use CoffeeHouse\Objects\Cache\TranslateCache;
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
            $input = $this->coffeeHouse->getDatabase()->real_escape_string(urlencode($translationResults->Input));
            $output = $this->coffeeHouse->getDatabase()->real_escape_string(urlencode($translationResults->Output));
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

        /**
         * Gets an existing cache record from the database
         *
         * @param string $search_method
         * @param string $value
         * @return TranslateCache
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TranslationCacheNotFoundException
         */
        public function getCache(string $search_method, string $value): TranslateCache
        {
            switch($search_method)
            {
                case TranslationCacheSearchMethod::byId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case TranslationCacheSearchMethod::byPublicID:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = $this->coffeeHouse->getDatabase()->real_escape_string($value);
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("translate_cache", [
                "id",
                "public_id",
                "source",
                "target",
                "processing_engine",
                "input",
                "output",
                "last_updated_timestamp",
                "created_timestamp"
            ], $search_method, $value);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new TranslationCacheNotFoundException();
                }
                else
                {
                    $Row["input"] = urldecode($Row["input"]);
                    $Row["output"] = urldecode($Row["output"]);
                    return(TranslateCache::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates an existing cache record.
         *
         * @param TranslateCache $translateCache
         * @return bool
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws TranslationCacheNotFoundException
         */
        public function updateCache(TranslateCache $translateCache): bool
        {
            $this->getCache(TranslationCacheSearchMethod::byId, $translateCache->ID);

            $Query = QueryBuilder::update("translate_cache", [
                "output" => $this->coffeeHouse->getDatabase()->real_escape_string(urlencode($translateCache->Output)),
                "last_updated_timestamp" => (int)time()
            ], "id", (int)$translateCache->ID);
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