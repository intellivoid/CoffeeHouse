<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;

    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Classes\Validation;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EmotionPredictionCacheNotFoundException;
    use CoffeeHouse\Exceptions\EngineNotImplementedException;
    use CoffeeHouse\Exceptions\InvalidInputException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\InvalidTextInputException;
    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Exceptions\TranslationCacheNotFoundException;
    use CoffeeHouse\Exceptions\TranslationException;
    use CoffeeHouse\Exceptions\UnsupportedLanguageException;
    use CoffeeHouse\Objects\LargeGeneralization;
    use CoffeeHouse\Objects\Results\EmotionPredictionResults;
    use CoffeeHouse\Objects\Results\EmotionPredictionSentencesResults;

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
         * @param string $source_language
         * @param bool $cache
         * @return EmotionPredictionResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws InvalidInputException
         * @throws InvalidSearchMethodException
         * @throws InvalidServerInterfaceModuleException
         * @throws MalformedDataException
         * @throws ServerInterfaceException
         * @throws UnsupportedLanguageException
         * @throws EngineNotImplementedException
         * @throws InvalidLanguageException
         * @throws InvalidTextInputException
         * @throws TranslationCacheNotFoundException
         * @throws TranslationException
         * @noinspection DuplicatedCode
         */
        public function predict(string $input, $source_language="en", bool $cache=True): EmotionPredictionResults
        {
            if(strlen($input) == 0)
            {
                throw new InvalidInputException();
            }

            if($source_language !== null)
            {
                $source_language = strtolower($source_language);

                if($source_language !== "en")
                {
                    if($source_language == "auto")
                    {
                        $source_language = $this->coffeeHouse->getLanguagePrediction()->predict($input)->combineResults()[0]->Language;
                    }

                    $source_language = Utilities::convertToISO6391($source_language);

                    if($source_language !== "en")
                    {
                        if(Validation::googleTranslateSupported($source_language) == false)
                        {
                            throw new UnsupportedLanguageException("The language '$source_language' is unsupported");
                        }

                        $input = $this->coffeeHouse->getTranslator()->translate($input, "en", $source_language)->Output;
                    }
                }
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
         * Predicts emotional data from sentences
         *
         * @param string $input
         * @param string $source_language
         * @param bool $cache
         * @return EmotionPredictionSentencesResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws EngineNotImplementedException
         * @throws InvalidInputException
         * @throws InvalidLanguageException
         * @throws InvalidSearchMethodException
         * @throws InvalidServerInterfaceModuleException
         * @throws InvalidTextInputException
         * @throws MalformedDataException
         * @throws ServerInterfaceException
         * @throws TranslationCacheNotFoundException
         * @throws TranslationException
         * @throws UnsupportedLanguageException
         */
        public function predictSentences(string $input, $source_language="en", bool $cache=True): EmotionPredictionSentencesResults
        {
            if(strlen($input) == 0)
            {
                throw new InvalidInputException();
            }

            if($source_language !== null)
            {
                $source_language = strtolower($source_language);

                if($source_language !== "en")
                {
                    if($source_language == "auto")
                    {
                        $source_language = $this->coffeeHouse->getLanguagePrediction()->predict($input)->combineResults()[0]->Language;
                    }

                    $source_language = Utilities::convertToISO6391($source_language);

                    if($source_language !== "en")
                    {
                        if(Validation::googleTranslateSupported($source_language) == false)
                        {
                            throw new UnsupportedLanguageException("The language '$source_language' is unsupported");
                        }

                        $input = $this->coffeeHouse->getTranslator()->translate($input, "en", $source_language)->Output;
                    }
                }
            }

            $Sentences = $this->coffeeHouse->getCoreNLP()->sentenceSplit($input);
            $EmotionPredictionSentencesResultsObject = new EmotionPredictionSentencesResults();
            $EmotionPredictionSentencesResultsObject->Text = $input;
            $EmotionPredictionSentencesResultsObject->EmotionPredictionSentences = [];

            foreach($Sentences->Sentences as $sentence)
            {
                $EmotionSentenceObject = new EmotionPredictionSentencesResults\EmotionPredictionSentence();
                $EmotionSentenceObject->Text = $sentence->Text;
                $EmotionSentenceObject->OffsetBegin = $sentence->OffsetBegin;
                $EmotionSentenceObject->OffsetEnd = $sentence->OffsetEnd;
                $EmotionSentenceObject->EmotionPredictionResults = $this->predict($sentence->Text, $source_language, $cache);

                $EmotionPredictionSentencesResultsObject->EmotionPredictionSentences[] = $EmotionSentenceObject;
            }

            $EmotionPredictionSentencesResultsObject->calculateCombinedSentiment();

            return $EmotionPredictionSentencesResultsObject;
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