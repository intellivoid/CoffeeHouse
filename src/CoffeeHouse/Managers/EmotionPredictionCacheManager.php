<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EmotionPredictionCacheNotFoundException;
    use CoffeeHouse\Objects\Cache\EmotionPredictionCache;
    use CoffeeHouse\Objects\Results\EmotionPredictionResults;
    use msqg\QueryBuilder;
    use ZiProto\ZiProto;

    /**
     * Class EmotionPredictionCacheManager
     * @package CoffeeHouse\Managers
     */
    class EmotionPredictionCacheManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * EmotionPredictionCacheManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * @param string $input
         * @param EmotionPredictionResults $predictionResults
         * @return bool
         * @throws DatabaseException
         */
        public function registerCache(string $input, EmotionPredictionResults $predictionResults): bool
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));
            $predictions = $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode($predictionResults->valuesToArray()));
            $top_prediction = (float)$predictionResults->TopValue;
            $top_emotion = $this->coffeeHouse->getDatabase()->real_escape_string($predictionResults->TopEmotion);
            $created_timestamp = (int)time();
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::insert_into("emotion_prediction_cache", array(
                "hash" => $hash,
                "predictions" => $predictions,
                "top_prediction" => $top_prediction,
                "top_emotion" => $top_emotion,
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
         * Gets a cache record from the database
         *
         * @param string $input
         * @return EmotionPredictionCache
         * @throws DatabaseException
         * @throws EmotionPredictionCacheNotFoundException
         */
        public function getCache(string $input): EmotionPredictionCache
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));

            $Query = QueryBuilder::select("emotion_prediction_cache", [
                "id",
                "hash",
                "predictions",
                "top_prediction",
                "top_emotion",
                "last_updated",
                "created"
            ], "hash", $hash, null, null, 1);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new EmotionPredictionCacheNotFoundException();
                }
                else
                {
                    $Row["predictions"] = ZiProto::decode($Row["predictions"]);
                    return(EmotionPredictionCache::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates an existing cache record in the database
         *
         * @param EmotionPredictionCache $emotionPredictionCache
         * @param EmotionPredictionResults $emotionPredictionResults
         * @return bool
         * @throws DatabaseException
         */
        public function updateCache(EmotionPredictionCache $emotionPredictionCache, EmotionPredictionResults $emotionPredictionResults): bool
        {
            $id = (int)$emotionPredictionCache->ID;
            $predictions = $this->coffeeHouse->getDatabase()->real_escape_string(ZiProto::encode($emotionPredictionResults->valuesToArray()));
            $top_prediction = (float)$emotionPredictionResults->TopValue;
            $top_emotion = $this->coffeeHouse->getDatabase()->real_escape_string($emotionPredictionResults->TopEmotion);
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::update('emotion_prediction_cache', array(
                "predictions" => $predictions,
                "top_prediction" => $top_prediction,
                "top_emotion" => $top_emotion,
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