<?php


    namespace CoffeeHouse\Objects\Cache;

    use CoffeeHouse\Abstracts\TranslateProcessingEngine;

    /**
     * Class TranslateCache
     * @package CoffeeHouse\Objects\Cache
     */
    class TranslateCache
    {
        /**
         * The Unique Internal Database ID for this cache record
         *
         * @var int|null
         */
        public ?int $ID;

        /**
         * The Unique Public ID for this cache record
         *
         * @var string|null
         */
        public ?string $PublicID;

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
         * The Unix Timestamp for when this record was last updated
         *
         * @var int|null
         */
        public ?int $LastUpdatedTimestamp;

        /**
         * The Unix Timestamp for when this record was created
         *
         * @var int|null
         */
        public ?int $CreatedTimestamp;

        /**
         * Returns an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "id" => $this->ID,
                "public_id" => $this->PublicID,
                "source" => $this->Source,
                "target" => $this->Target,
                "processing_engine"=> $this->ProcessingEngine,
                "input" => $this->Input,
                "output" => $this->Output,
                "last_updated_timestamp" => $this->LastUpdatedTimestamp,
                "created_timestamp" => $this->CreatedTimestamp
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return TranslateCache
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): TranslateCache
        {
            $TranslateCacheObject = new TranslateCache();

            if(isset($data["id"]))
            {
                $TranslateCacheObject->ID = (int)$data["id"];
            }

            if(isset($data["public_id"]))
            {
                $TranslateCacheObject->PublicID = $data["public_id"];
            }

            if(isset($data["source"]))
            {
                $TranslateCacheObject->Source = $data["source"];
            }

            if(isset($data["target"]))
            {
                $TranslateCacheObject->Target = $data["target"];
            }

            if(isset($data["processing_engine"]))
            {
                $TranslateCacheObject->ProcessingEngine = $data["processing_engine"];
            }

            if(isset($data["input"]))
            {
                $TranslateCacheObject->Input = $data["input"];
            }

            if(isset($data["output"]))
            {
                $TranslateCacheObject->Output = $data["output"];
            }

            if(isset($data["last_updated_timestamp"]))
            {
                $TranslateCacheObject->LastUpdatedTimestamp = (int)$data["last_updated_timestamp"];
            }

            if(isset($data["created_timestamp"]))
            {
                $TranslateCacheObject->CreatedTimestamp = (int)$data["created_timestamp"];
            }

            return $TranslateCacheObject;
        }
    }