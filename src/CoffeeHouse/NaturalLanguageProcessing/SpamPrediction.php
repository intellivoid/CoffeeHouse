<?php


    namespace CoffeeHouse\NaturalLanguageProcessing;


    use CoffeeHouse\Abstracts\GeneralizedClassificationSearchMethod;
    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Classes\Validation;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EngineNotImplementedException;
    use CoffeeHouse\Exceptions\GeneralizedClassificationNotFoundException;
    use CoffeeHouse\Exceptions\InvalidInputException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\InvalidTextInputException;
    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Exceptions\SpamPredictionCacheNotFoundException;
    use CoffeeHouse\Exceptions\TranslationCacheNotFoundException;
    use CoffeeHouse\Exceptions\TranslationException;
    use CoffeeHouse\Exceptions\UnsupportedLanguageException;
    use CoffeeHouse\Objects\GeneralizedClassification;
    use CoffeeHouse\Objects\LargeGeneralization;
    use CoffeeHouse\Objects\Results\SpamPredictionResults;
    use CoffeeHouse\Objects\Results\SpamPredictionSentencesResults;

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
         * @param bool $generalize
         * @param string $generalized_id
         * @param bool $cache
         * @param string $source_language
         * @return SpamPredictionResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws EngineNotImplementedException
         * @throws GeneralizedClassificationNotFoundException
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
         * @noinspection DuplicatedCode
         */
        public function predict(string $input, bool $generalize=false, string $generalized_id="None", bool $cache=true, $source_language="en"): SpamPredictionResults
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

            $SpamPredictionCache = null;
            $PredictionGeneralization = null;

            if($generalize)
            {
                if($generalized_id == "None")
                {
                    $PredictionGeneralization = $this->createGeneralized();
                }
                else
                {
                    $PredictionGeneralization = $this->getGeneralized($generalized_id);
                }
            }

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

                        if($generalize)
                        {
                            /** @var GeneralizedClassification $PredictionGeneralization */
                            $PredictionGeneralization['spam_generalized']->addValue($PredictionResults->SpamPrediction);
                            $PredictionGeneralization['ham_generalized']->addValue($PredictionResults->HamPrediction);

                            $PredictionResults->GeneralizedSpam = $PredictionGeneralization['spam_generalized']->Results;
                            $PredictionResults->GeneralizedHam = $PredictionGeneralization['ham_generalized']->Results;
                            $PredictionResults->GeneralizedID = $PredictionGeneralization['generalized_id'];

                            $this->updateGeneralized($PredictionGeneralization);
                        }

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

            if($generalize)
            {
                /** @var GeneralizedClassification $PredictionGeneralization */
                $PredictionGeneralization['spam_generalized']->addValue($PredictionResults->SpamPrediction);
                $PredictionGeneralization['ham_generalized']->addValue($PredictionResults->HamPrediction);

                $PredictionResults->GeneralizedSpam = $PredictionGeneralization['spam_generalized']->Results;
                $PredictionResults->GeneralizedHam = $PredictionGeneralization['ham_generalized']->Results;
                $PredictionResults->GeneralizedID = $PredictionGeneralization['generalized_id'];

                $this->updateGeneralized($PredictionGeneralization);
            }

            return $PredictionResults;
        }

        /**
         * Predicts spam data from sentences
         *
         * @param string $input
         * @param string $source_language
         * @param bool $cache
         * @return SpamPredictionSentencesResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws EngineNotImplementedException
         * @throws GeneralizedClassificationNotFoundException
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
        public function predictSentences(string $input, $source_language="en", bool $cache=True): SpamPredictionSentencesResults
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

                    if(Validation::googleTranslateSupported($source_language) == false)
                    {
                        throw new UnsupportedLanguageException("The language '$source_language' is unsupported");
                    }

                    $input = $this->coffeeHouse->getTranslator()->translate($input, "en", $source_language)->Output;
                }
            }

            $Sentences = $this->coffeeHouse->getCoreNLP()->sentenceSplit($input);
            $SpamPredictionSentencesResultsObject = new SpamPredictionSentencesResults();
            $SpamPredictionSentencesResultsObject->Text = $input;
            $SpamPredictionSentencesResultsObject->SpamPredictionSentences = [];

            foreach($Sentences->Sentences as $sentence)
            {
                $SpamSentenceObject = new SpamPredictionSentencesResults\SpamPredictionSentence();
                $SpamSentenceObject->Text = $sentence->Text;
                $SpamSentenceObject->OffsetBegin = $sentence->OffsetBegin;
                $SpamSentenceObject->OffsetEnd = $sentence->OffsetEnd;
                $SpamSentenceObject->SpamPredictionResults = $this->predict($sentence->Text, false, "None", $cache, $source_language);

                $SpamPredictionSentencesResultsObject->SpamPredictionSentences[] = $SpamSentenceObject;
            }

            $SpamPredictionSentencesResultsObject->calculateCombinedPredictions();

            return $SpamPredictionSentencesResultsObject;
        }

        /**
         * Returns the generalized results as an array
         *
         * @param string $generalized_id
         * @return array
         * @throws DatabaseException
         * @throws GeneralizedClassificationNotFoundException
         * @throws InvalidSearchMethodException
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function getGeneralized(string $generalized_id): array
        {
            $generalized_ids = explode(':', $generalized_id);

            if(count($generalized_ids) !== 2)
            {
                throw new GeneralizedClassificationNotFoundException();
            }

            return array(
                'spam_generalized' => $this->coffeeHouse->getGeneralizedClassificationManager()->get(
                    GeneralizedClassificationSearchMethod::byPublicID, $generalized_ids[0]
                ),
                'ham_generalized' => $this->coffeeHouse->getGeneralizedClassificationManager()->get(
                    GeneralizedClassificationSearchMethod::byPublicID, $generalized_ids[1]
                ),
                'generalized_id' => $generalized_id
            );
        }

        /**
         * Creates a generalized structure
         *
         * @return array
         * @throws DatabaseException
         * @throws GeneralizedClassificationNotFoundException
         * @throws InvalidSearchMethodException
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function createGeneralized(): array
        {
            $spam_generalized = $this->coffeeHouse->getGeneralizedClassificationManager()->create(50);
            $ham_generalized = $this->coffeeHouse->getGeneralizedClassificationManager()->create(50);

            return array(
                'spam_generalized' => $spam_generalized,
                'ham_generalized' => $ham_generalized,
                'generalized_id' => $spam_generalized->PublicID . ":" . $ham_generalized->PublicID
            );
        }

        /**
         * Updates an existing generalized structure
         *
         * @param array $generalization_array
         * @return bool
         * @throws DatabaseException
         * @throws GeneralizedClassificationNotFoundException
         * @throws InvalidSearchMethodException
         */
        public function updateGeneralized(array $generalization_array): bool
        {
            $this->coffeeHouse->getGeneralizedClassificationManager()->update(
                $generalization_array['spam_generalized']
            );

            $this->coffeeHouse->getGeneralizedClassificationManager()->update(
                $generalization_array['ham_generalized']
            );

            return true;
        }

        /**
         * Generalized the Spam Prediction results using large generalization (more efficient)
         *
         * @param LargeGeneralization $largeGeneralization
         * @param SpamPredictionResults $spamPredictionResults
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws MalformedDataException
         * @throws NoResultsFoundException
         */
        public function largeGeneralize(LargeGeneralization $largeGeneralization, SpamPredictionResults $spamPredictionResults): LargeGeneralization
        {

            $largeGeneralization->add("ham", $spamPredictionResults->HamPrediction, false);
            $largeGeneralization->add("spam", $spamPredictionResults->SpamPrediction, false);
            $largeGeneralization->sortProbabilities(true);

            $this->coffeeHouse->getLargeGeneralizedClassificationManager()->update($largeGeneralization);

            return $largeGeneralization;
        }
    }