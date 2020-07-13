<?php /** @noinspection PhpUnused */


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\LanguagePredictionCacheNotFoundException;
    use CoffeeHouse\Objects\Cache\LanguagePredictionCache;
    use CoffeeHouse\Objects\Results\LanguagePredictionResults;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class LanguagePredictionCacheManager
     * @package CoffeeHouse\Managers
     */
    class LanguagePredictionCacheManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * LanguagePredictionCacheManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Registers a cache result into the database
         *
         * @param string $input
         * @param LanguagePredictionResults $predictionResults
         * @return bool
         * @throws DatabaseException
         */
        public function registerCache(string $input, LanguagePredictionResults $predictionResults): bool
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));
            $created_timestamp = (int)time();
            $last_updated_timestamp = (int)time();
            $dltc_results = null;
            $cld_results = null;
            $ld_results = null;

            if($predictionResults->DLTC_Results !== null)
            {
                $dltc_results = $this->coffeeHouse->getDatabase()->real_escape_string(
                    ZiProto::encode($predictionResults->toArray()["dltc_results"])
                );
            }

            if($predictionResults->CLD_Results !== null)
            {
                $cld_results = $this->coffeeHouse->getDatabase()->real_escape_string(
                    ZiProto::encode($predictionResults->toArray()["cld_results"])
                );
            }

            if($predictionResults->LD_Results !== null)
            {
                $ld_results = $this->coffeeHouse->getDatabase()->real_escape_string(
                    ZiProto::encode($predictionResults->toArray()["ld_results"])
                );
            }

            $Query = QueryBuilder::insert_into('language_prediction_cache', array(
                "hash" => $hash,
                "dltc_results" => $dltc_results,
                "cld_results" => $cld_results,
                "ld_results" => $ld_results,
                "last_updated" => $last_updated_timestamp,
                "created" => $created_timestamp
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
         * @param string $input
         * @return LanguagePredictionCache
         * @throws DatabaseException
         * @throws LanguagePredictionCacheNotFoundException
         */
        public function getCache(string $input): LanguagePredictionCache
        {
            /** @noinspection DuplicatedCode */
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));

            $Query = QueryBuilder::select('language_prediction_cache', [
                'id',
                'hash',
                'dltc_results',
                'cld_results',
                'ld_results',
                'last_updated',
                'created'
            ], 'hash', $hash, null, null, 1);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new LanguagePredictionCacheNotFoundException();
                }
                else
                {
                    if($Row["dltc_results"] !== null)
                    {
                        $Row["dltc_results"] = ZiProto::decode($Row["dltc_results"]);
                    }

                    if($Row["cld_results"] !== null)
                    {
                        $Row["cld_results"] = ZiProto::decode($Row["cld_results"]);
                    }

                    if($Row["ld_results"] !== null)
                    {
                        $Row["ld_results"] = ZiProto::decode($Row["ld_results"]);
                    }

                    return(LanguagePredictionCache::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }
    }