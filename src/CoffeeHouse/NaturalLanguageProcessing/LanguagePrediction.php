<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
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

            $Results = $this->coffeeHouse->getServerInterface()->sendRequest(
                ServerInterfaceModule::LanguagePrediction, "/", array(
                    "input" => $input,
                    "dltc" => (int)$dltc,
                    "cld" => (int)$cld,
                    "ld" => (int)$ld
                )
            );

            $PredictionResults = LanguagePredictionResults::fromArray(json_decode($Results, true)['results']);

            return $PredictionResults;
        }
    }