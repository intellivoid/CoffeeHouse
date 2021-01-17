<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;

    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EmotionPredictionCacheNotFoundException;
    use CoffeeHouse\Exceptions\InvalidInputException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Objects\LargeGeneralization;
    use CoffeeHouse\Objects\Results\EmotionPredictionResults;

    /**
     * Class EmotionPrediction
     * @package CoffeeHouse\NaturalLanguageProcessing
     */
    class EmotionPrediction
    {
        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * LanguagePrediction constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Predicts the language of a given input
         *
         * @param string $input
         * @param bool $cache
         * @return EmotionPredictionResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws InvalidInputException
         * @throws InvalidServerInterfaceModuleException
         * @throws ServerInterfaceException
         */
        public function predict(string $input, bool $cache=True): EmotionPredictionResults
        {
            if(strlen($input) == 0)
            {
                throw new InvalidInputException();
            }

            $EmotionPredictionCache = null;


            if($cache)
            {
                try
                {
                    $EmotionPredictionCache = $this->coffeeHouse->getEmotionPredictionCacheManager()->getCache($input);
                }
                catch (EmotionPredictionCacheNotFoundException $e)
                {
                    unset($e);
                    $EmotionPredictionCache = null;
                }
                catch(DatabaseException $e)
                {
                    throw $e;
                }

                if($EmotionPredictionCache !== null)
                {
                    if(((int)time() - $EmotionPredictionCache->LastUpdated) < 86400)
                    {
                        return $EmotionPredictionCache->toResults();
                    }
                }

            }

            $Results = $this->coffeeHouse->getServerInterface()->sendRequest(
                ServerInterfaceModule::EmotionPrediction, "/", ["input" => $input]
            );

            $PredictionResults = EmotionPredictionResults::fromResults(json_decode($Results, true)['results']);

            if($cache)
            {
                if($EmotionPredictionCache == null)
                {
                    $this->coffeeHouse->getEmotionPredictionCacheManager()->registerCache($input, $PredictionResults);
                }
                else
                {
                    if(((int)time() - $EmotionPredictionCache->LastUpdated) > 86400)
                    {
                        $this->coffeeHouse->getEmotionPredictionCacheManager()->updateCache($EmotionPredictionCache, $PredictionResults);
                    }
                }
            }

            return $PredictionResults;
        }

        /**
         * Generalized the language predictions and returns the results
         *
         * @param LargeGeneralization $largeGeneralization
         * @param EmotionPredictionResults $languagePredictionResults
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws MalformedDataException
         * @throws NoResultsFoundException
         */
        public function generalize(LargeGeneralization $largeGeneralization, EmotionPredictionResults $languagePredictionResults): LargeGeneralization
        {
            foreach($languagePredictionResults->valuesToArray() as $emotion => $prediction_value)
            {
                $largeGeneralization->add($emotion, $prediction_value, false);
            }

            $largeGeneralization->sortProbabilities(true);
            $this->coffeeHouse->getLargeGeneralizedClassificationManager()->update($largeGeneralization);
            return $largeGeneralization;
        }
    }