<?php


    namespace CoffeeHouse\Managers;


    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Objects\Results\SpamPredictionResults;
    use msqg\QueryBuilder;

    /**
     * Class SpamPredictionCacheManager
     * @package CoffeeHouse\Managers
     */
    class SpamPredictionCacheManager
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * SpamPredictionCacheManager constructor.
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
         * @param SpamPredictionResults $predictionResults
         * @return bool
         * @throws DatabaseException
         */
        public function registerCache(string $input, SpamPredictionResults $predictionResults): bool
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));
            $ham = (float)$predictionResults->HamPrediction;
            $spam = (float)$predictionResults->SpamPrediction;
            $created_timestamp = (int)time();
            $last_updated_timestamp = (int)time();

            $Query = QueryBuilder::insert_into('spam_prediction_cache', array(
                'hash' => $hash,
                'ham' => $ham,
                'spam' => $spam,
                'last_updated' => $last_updated_timestamp,
                'created' => $created_timestamp
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

        public function getCache(string $input): bool
        {
            $hash = $this->coffeeHouse->getDatabase()->real_escape_string(Hashing::input($input));

            $Query = QueryBuilder::select('spam_prediction_cache', [
                'id',
                'hash',
                'ham_calculation',
                'spam_calculation',
                'last_updated',
                'created'
            ], 'hash', $hash);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new ForeignSessionNotFoundException();
                }
                else
                {
                    $Row['headers'] = ZiProto::decode($Row['headers']);
                    $Row['cookies'] = ZiProto::decode($Row['cookies']);
                    $Row['variables'] = ZiProto::decode($Row['variables']);
                    return(ForeignSession::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }
    }