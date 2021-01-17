<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\NsfwClassificationCacheNotFoundException;
    use CoffeeHouse\Objects\Cache\NsfwClassificationCache;
    use CoffeeHouse\Objects\Results\NsfwClassificationResults;
    use msqg\QueryBuilder;

    /**
     * Class NsfwClassificationCacheManager
     * @package CoffeeHouse\Managers
     */
    class NsfwClassificationCacheManager
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
         * Registers a cache record from the results
         *
         * @param NsfwClassificationResults $predictionResults
         * @return bool
         * @throws DatabaseException
         */
        public function registerCache(NsfwClassificationResults $predictionResults): bool
        {
            $image_type = $this->coffeeHouse->getDatabase()->real_escape_string($predictionResults->ImageType);
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string($predictionResults->ContentHash);
            $safe_prediction = (float)$predictionResults->SafePrediction;
            $unsafe_prediction = (float)$predictionResults->UnsafePrediction;
            $is_nsfw = (int)($predictionResults->UnsafePrediction > $predictionResults->SafePrediction);
            $created_timestamp = (int)time();
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::insert_into("nsfw_image_classification_cache", array(
                "hash" => $hash,
                "image_type" => $image_type,
                "safe_prediction" => $safe_prediction,
                "unsafe_prediction" => $unsafe_prediction,
                "is_nsfw" => $is_nsfw,
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
         * Returns a cache record from the database
         *
         * @param string $content_hash
         * @return NsfwClassificationCache
         * @throws DatabaseException
         * @throws NsfwClassificationCacheNotFoundException
         */
        public function getCache(string $content_hash): NsfwClassificationCache
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string($content_hash);

            $Query = QueryBuilder::select("nsfw_image_classification_cache", [
                "id",
                "hash",
                "image_type",
                "safe_prediction",
                "unsafe_prediction",
                "is_nsfw",
                "last_updated",
                "created"
            ], "hash", $hash, null, null, 1);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new NsfwClassificationCacheNotFoundException();
                }
                else
                {
                    return(NsfwClassificationCache::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates an existing classification record
         *
         * @param NsfwClassificationCache $nsfwClassificationCache
         * @param NsfwClassificationResults $nsfwClassificationResults
         * @return bool
         * @throws DatabaseException
         */
        public function updateCache(NsfwClassificationCache $nsfwClassificationCache, NsfwClassificationResults $nsfwClassificationResults): bool
        {
            $id = (int)$nsfwClassificationCache->ID;
            $safe_prediction = (float)$nsfwClassificationResults->SafePrediction;
            $unsafe_prediction = (float)$nsfwClassificationResults->UnsafePrediction;
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::update('nsfw_image_classification_cache', array(
                "safe_prediction" => $safe_prediction,
                "unsafe_prediction" => $unsafe_prediction,
                "is_nsfw" => (int)($unsafe_prediction > $safe_prediction),
                "last_updated" => $last_updated_timestamp
            ), "id", $id);

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