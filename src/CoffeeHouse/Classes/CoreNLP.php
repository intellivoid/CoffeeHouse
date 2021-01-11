<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EngineNotImplementedException;
    use CoffeeHouse\Exceptions\InvalidInputException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\InvalidTextInputException;
    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Exceptions\TranslationCacheNotFoundException;
    use CoffeeHouse\Exceptions\TranslationException;
    use CoffeeHouse\Exceptions\UnsupportedLanguageException;
    use CoffeeHouse\Objects\ProcessedNLP\Sentence;

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
         * Processes the text input into objects
         *
         * @param string $input
         * @param string $source_language
         * @return Sentence[]
         * @throws DatabaseException
         * @throws EngineNotImplementedException
         * @throws InvalidLanguageException
         * @throws InvalidSearchMethodException
         * @throws InvalidServerInterfaceModuleException
         * @throws InvalidTextInputException
         * @throws ServerInterfaceException
         * @throws TranslationCacheNotFoundException
         * @throws TranslationException
         * @throws UnsupportedLanguageException
         * @throws InvalidInputException
         * @throws MalformedDataException
         */
        public function processText(string $input, string $source_language="en"): array
        {
            if(Validation::coreNlpInput($input) == false)
            {
                throw new InvalidTextInputException("The given text input is invalid");
            }

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
                $sentences[] = Sentence::fromArray($sentence);

            $results = [
                "text" => $input,
                "sentences" => $sentences
            ];


            return $results;
        }
    }