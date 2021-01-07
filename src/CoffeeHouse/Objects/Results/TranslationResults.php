<?php


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Abstracts\TranslateProcessingEngine;

    /**
     * Class TranslationResults
     * @package CoffeeHouse\Objects\Results
     */
    class TranslationResults
    {
        /**
         * The source of the translation
         *
         * @var string|null
         */
        public ?string $Source;

        /**
         * The target language for the output
         *
         * @var string|null
         */
        public ?string $Target;

        /**
         * @var string|TranslateProcessingEngine|null
         */
        public ?string $ProcessingEngine;

        /**
         * The input to translate
         *
         * @var string|null
         */
        public ?string $Input;

        /**
         * The output of the translation
         *
         * @var string|null
         */
        public ?string $Output;

        /**
         * Returns an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "source" => $this->Source,
                "target" => $this->Target,
                "processing_engine"=> $this->ProcessingEngine,
                "input" => $this->Input,
                "output" => $this->Output,
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return TranslationResults
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): TranslationResults
        {
            $TranslationResultsObject = new TranslationResults();

            if(isset($data["source"]))
            {
                $TranslationResultsObject->Source = $data["source"];
            }

            if(isset($data["target"]))
            {
                $TranslationResultsObject->Target = $data["target"];
            }

            if(isset($data["processing_engine"]))
            {
                $TranslationResultsObject->ProcessingEngine = $data["processing_engine"];
            }

            if(isset($data["input"]))
            {
                $TranslationResultsObject->Input = $data["input"];
            }

            if(isset($data["output"]))
            {
                $TranslationResultsObject->Output = $data["output"];
            }

            return $TranslationResultsObject;
        }
    }