<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Exceptions\SpamPredictionCacheNotFoundException;
    use CoffeeHouse\Objects\Cache\SpamPredictionCache;
    use CoffeeHouse\Objects\Results\SpamPredictionResults;

    /**
     * Class SpamPrediction
     * @package CoffeeHouse\NaturalLanguageProcessing
     */
    class SpamPrediction
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * SpamPrediction constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Predicts if the given input is spam or not
         *
         * @param string $input
         * @param bool $cache
         * @return SpamPredictionResults
         * @throws DatabaseException
         * @throws InvalidServerInterfaceModuleException
         * @throws ServerInterfaceException
         */
        public function predict(string $input, bool $cache=false): SpamPredictionResults
        {
            $SpamPredictionCache = null;

            if($cache)
            {
                try
                {
                    $SpamPredictionCache = $this->coffeeHouse->getSpamPredictionCacheManager()->getCache($input);
                }
                catch (SpamPredictionCacheNotFoundException $e)
                {
                    unset($e);
                    $SpamPredictionCache = null;
                }
                catch(DatabaseException $e)
                {
                    throw $e;
                }

                if($SpamPredictionCache !== null)
                {
                    if(((int)time() - $SpamPredictionCache->LastUpdated) < 86400)
                    {
                        $PredictionResults = new SpamPredictionResults();
                        $PredictionResults->SpamPrediction = $SpamPredictionCache->SpamCalculation;
                        $PredictionResults->HamPrediction = $SpamPredictionCache->HamCalculation;
                        return $PredictionResults;
                    }
                }
            }

            $Results = $this->coffeeHouse->getServerInterface()->sendRequest(
                ServerInterfaceModule::SpamPrediction, "/", array("input" => $input)
            );

            $PredictionResults = SpamPredictionResults::fromArray(json_decode($Results, true)['results']);

            if($cache)
            {
                if($SpamPredictionCache == null)
                {
                    $this->coffeeHouse->getSpamPredictionCacheManager()->registerCache($input, $PredictionResults);
                }
                else
                {
                    if(((int)time() - $SpamPredictionCache->LastUpdated) > 86400)
                    {
                        $this->coffeeHouse->getSpamPredictionCacheManager()->updateCache($SpamPredictionCache, $PredictionResults);
                    }
                }
            }

            return $PredictionResults;
        }
    }