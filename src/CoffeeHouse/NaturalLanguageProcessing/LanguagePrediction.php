<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\LanguagePredictionCacheNotFoundException;
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

        public function predict(string $input, $dltc=true, $cld=true, $ld=true, bool $cache=true): LanguagePredictionResults
        {
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
                        print("From cache" . PHP_EOL);
                        $PredictionResults = new LanguagePredictionResults();

                        if($LanguagePredictionCache->DLTC_Results !== null)
                        {
                            $PredictionResults->DLTC_Results = array();
                            foreach($LanguagePredictionCache->DLTC_Results as $result)
                            {
                                $PredictionResults->DLTC_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result);
                            }
                        }

                        if($LanguagePredictionCache->CLD_Results !== null)
                        {
                            $PredictionResults->CLD_Results = array();
                            foreach($LanguagePredictionCache->CLD_Results as $result)
                            {
                                $PredictionResults->CLD_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result);
                            }
                        }

                        if($LanguagePredictionCache->LD_Results !== null)
                        {
                            $PredictionResults->LD_Results = array();
                            foreach($LanguagePredictionCache->LD_Results as $result)
                            {
                                $PredictionResults->LD_Results[] = \CoffeeHouse\Objects\Results\LanguagePrediction::fromArray($result);
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
    }