<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
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
    use CoffeeHouse\Objects\ProcessedNLP\Sentence;
    use CoffeeHouse\Objects\Results\CoreNLP\NamedEntitiesResults;
    use CoffeeHouse\Objects\Results\CoreNLP\PartOfSpeechResults;
    use CoffeeHouse\Objects\Results\CoreNLP\SentenceSplitResults;
    use CoffeeHouse\Objects\Results\CoreNLP\SentimentResults;

    /**
     * Class CoreNLP
     * @package CoffeeHouse\Classes
     */
    class CoreNLP
    {
        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * CoreNLP constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {

            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Invokes a request to the CoreNLP server
         *
         * @param string $input
         * @param array $annotators
         * @return array
         * @throws InvalidServerInterfaceModuleException
         * @throws ServerInterfaceException
         * @throws CoffeeHouseUtilsNotReadyException
         */
        public function invoke(string $input, array $annotators): array
        {
            $properties_parsed = [
                "annotators" => implode(",", $annotators),
            ];

            $Results = $this->coffeeHouse->getServerInterface()->sendDataRequest(
                ServerInterfaceModule::CoreNLP, "/", $input, [
                    "properties" => json_encode($properties_parsed),
                    "piplineLanguage" => "en"
                ]
            );

            return json_decode($Results, true);
        }

        /**
         * Processes and validates the input and translates it from another language if needed
         *
         * @param string $input
         * @param string|null $source_language
         * @return string
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
         * @throws CoffeeHouseUtilsNotReadyException
         * @noinspection DuplicatedCode
         */
        private function validateInput(string $input, string $source_language=null): string
        {
            if(Validation::coreNlpInput($input) == false)
            {
                throw new InvalidTextInputException("The given text input is invalid");
            }

            if($source_language !== null)
            {
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

            return $input;
        }

        /**
         * Processes the text input into objects using multiple annotators
         *
         * @param string $input
         * @param string $source_language
         * @return Sentence[]
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
         * @throws CoffeeHouseUtilsNotReadyException
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function processText(string $input, string $source_language="en"): array
        {
            $input = $this->validateInput($input, $source_language);

            $results = $this->invoke($input, [
                "tokenize",
                "ssplit",
                "pos",
                "ner",
                "kbp",
                "sentiment",
                "regexner"
            ]);

            $sentences = [];
            foreach($results["sentences"] as $sentence)
                $sentences[] = Sentence::fromArray($input, $sentence);

            $results = [
                "text" => $input,
                "sentences" => $sentences
            ];

            return $results;
        }

        /**
         * Splits the sentences into an array using "ssplit" annotation.
         *
         * @param string $input
         * @return SentenceSplitResults
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
         * @throws CoffeeHouseUtilsNotReadyException
         * @noinspection DuplicatedCode
         */
        public function sentenceSplit(string $input): SentenceSplitResults
        {
            $input = $this->validateInput($input);

            $results = $this->invoke($input, ["ssplit"]);

            $sentences = [];
            foreach($results["sentences"] as $sentence)
                $sentences[] = Sentence::fromArray($input, $sentence);

            return SentenceSplitResults::fromSentences($input, $sentences);
        }

        /**
         * Tags the part of speech of sentences
         *
         * @param string $input
         * @param string $source_language
         * @return PartOfSpeechResults
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
         * @throws CoffeeHouseUtilsNotReadyException
         */
        public function posTag(string $input, string $source_language="en"): PartOfSpeechResults
        {
            $input = $this->validateInput($input, $source_language);

            $results = $this->invoke($input, ["tokenize", "ssplit", "pos"]);

            /** @var Sentence[] $sentences */
            $sentences = [];
            foreach($results["sentences"] as $sentence)
                $sentences[] = Sentence::fromArray($input, $sentence);

            return PartOfSpeechResults::fromSentences($input, $sentences);
        }

        /**
         * Extracts named entities from the text input
         *
         * @param string $input
         * @param string $source_language
         * @return NamedEntitiesResults
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
         * @throws CoffeeHouseUtilsNotReadyException
         */
        public function ner(string $input, string $source_language="en"): NamedEntitiesResults
        {
            $input = $this->validateInput($input, $source_language);

            $results = $this->invoke($input, ["tokenize", "ssplit", "ner", "regexner"]);

            $sentences = [];
            foreach($results["sentences"] as $sentence)
                $sentences[] = Sentence::fromArray($input, $sentence);

            return NamedEntitiesResults::fromSentences($input, $sentences);
        }

        /**
         * Performs a sentiment analysis on all the given text
         *
         * @param string $input
         * @param string $source_language
         * @return SentimentResults
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
         * @throws CoffeeHouseUtilsNotReadyException
         */
        public function sentiment(string $input, string $source_language="en"): SentimentResults
        {
            $input = $this->validateInput($input, $source_language);

            $results = $this->invoke($input, ["tokenize", "ssplit", "sentiment"]);

            $sentences = [];
            foreach($results["sentences"] as $sentence)
                $sentences[] = Sentence::fromArray($input, $sentence);

            return SentimentResults::fromSentences($input, $sentences);
        }

        /**
         * Updates the generalized sentiment results
         *
         * @param LargeGeneralization $largeGeneralization
         * @param SentimentResults $sentimentResults
         * @return LargeGeneralization
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws MalformedDataException
         * @throws NoResultsFoundException
         */
        public function generalizeSentiment(LargeGeneralization $largeGeneralization, SentimentResults $sentimentResults): LargeGeneralization
        {
            foreach($sentimentResults->Sentiment->Predictions as $prediction => $value)
            {
                $largeGeneralization->add($prediction, $value, false);
            }

            $largeGeneralization->sortProbabilities(true);
            $this->coffeeHouse->getLargeGeneralizedClassificationManager()->update($largeGeneralization);
            return $largeGeneralization;
        }
    }