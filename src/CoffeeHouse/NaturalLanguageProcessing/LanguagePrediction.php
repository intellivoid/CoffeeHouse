<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidInputException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\LanguagePredictionCacheNotFoundException;
    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Objects\LargeGeneralization;
    use CoffeeHouse\Objects\Results\LanguagePredictionResults;

    /**
     * Class LanguagePrediction
     * @package CoffeeHouse\NaturalLanguageProcessing
     */
    class LanguagePrediction
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

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
         * @param bool $dltc
         * @param bool $cld
         * @param bool $ld
         * @param bool $cache
         * @return LanguagePredictionResults
         * @throws DatabaseException
         * @throws InvalidInputException
         * @throws InvalidServerInterfaceModuleException
         * @throws MalformedDataException
         * @throws ServerInterfaceException
         * @throws CoffeeHouseUtilsNotReadyException
         */
        public function predict(string $input, $dltc=true, $cld=true, $ld=true, bool $cache=true): LanguagePredictionResults
        {
            if(strlen($input) == 0)
            {
                throw new InvalidInputException();
            }

            $LanguagePredictionCache = null;

            if($cache)
            {
                try
                {
                    $LanguagePredictionCache = $this->coffeeHouse->getLanguagePredictionCacheManager()->getCache($input);
                }
                catch (LanguagePredictionCacheNotFoundException $e)
                {
                    unset($e);
                    $LanguagePredictionCache = null;
                }
                catch(DatabaseException $e)
                {
                    throw $e;
                }

                if($LanguagePredictionCache !== null)
                {
                    if(((int)time() - $LanguagePredictionCache->LastUpdated) < 86400)
                    {
                        $PredictionResults = new LanguagePredictionResults();

                        if($LanguagePredictionCache->DLTC_Results !== null)
                        {
                            $PredictionResults->DLTC_Results = array();
                            foreach($LanguagePredictionCache->DLTC_Results as $result)
                            {
                                $PredictionResults->DLTC_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result, true);
                            }
                        }

                        if($LanguagePredictionCache->CLD_Results !== null)
                        {
                            $PredictionResults->CLD_Results = array();
                            foreach($LanguagePredictionCache->CLD_Results as $result)
                            {
                                $PredictionResults->CLD_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result, true);
                            }
                        }

                        if($LanguagePredictionCache->LD_Results !== null)
                        {
                            $PredictionResults->LD_Results = array();
                            foreach($LanguagePredictionCache->LD_Results as $result)
                            {
                                $PredictionResults->LD_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result, true);
                            }
                        }

                        return $PredictionResults;
                    }
                }

            }

            $Results = $this->coffeeHouse->getServerInterface()->sendRequest(
                ServerInterfaceModule::LanguagePrediction, "/", array(
                    "input" => $input,
                    "dltc" => (int)$dltc,
                    "cld" => (int)$cld,
                    "ld" => (int)$ld
                )
            );

            $PredictionResults = LanguagePredictionResults::fromArray(json_decode($Results, true)['results']);

            if($cache)
            {
                if($LanguagePredictionCache == null)
                {
                    $this->coffeeHouse->getLanguagePredictionCacheManager()->registerCache($input, $PredictionResults);
                }
                else
                {
                    if(((int)time() - $LanguagePredictionCache->LastUpdated) > 86400)
                    {
                        $this->coffeeHouse->getLanguagePredictionCacheManager()->updateCache($LanguagePredictionCache, $PredictionResults);
                    }
                }
            }

            return $PredictionResults;
        }

        /**
         * Generalized the language predictions and returns the results
         *
         * @param LargeGeneralization $largeGeneralization
         * @param LanguagePredictionResults $languagePredictionResults
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws MalformedDataException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         */
        public function generalize(LargeGeneralization $largeGeneralization, LanguagePredictionResults $languagePredictionResults): LargeGeneralization
        {
            $combined_results = $languagePredictionResults->combineResults();

            foreach($combined_results as $languagePredictions)
            {
                $largeGeneralization->add($languagePredictions->Language, $languagePredictions->Probability, false);
            }

            $largeGeneralization->sortProbabilities(true);
            $this->coffeeHouse->getLargeGeneralizedClassificationManager()->update($largeGeneralization);
            return $largeGeneralization;
        }
    }