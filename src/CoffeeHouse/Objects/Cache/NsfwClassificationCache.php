<?php


    namespace CoffeeHouse\Objects\Cache;

    use CoffeeHouse\Objects\Results\NsfwClassificationResults;

    /**
     * Class NsfwClassificationCache
     * @package CoffeeHouse\Objects\Cache
     */
    class NsfwClassificationCache
    {
        /**
         * The ID of the cache record
         *
         * @var int|null
         */
        public ?int $ID;

        /**
         * The image content hash (SHA256)
         *
         * @var string|null
         */
        public ?string $Hash;

        /**
         * The image type recognized from the exif data
         *
         * @var string|null
         */
        public ?string $ImageType;

        /**
         * The safe prediction value
         *
         * @var float|null
         */
        public ?float $SafePrediction;

        /**
         * The unsafe prediction value
         *
         * @var float|null
         */
        public ?float $UnsafePrediction;

        /**
         * An indication if this image is NSFW if the unsafe prediction is higher than the safe prediction
         *
         * @var bool|null
         */
        public ?bool $IsNSFW;

        /**
         * The Unix Timestamp for when this record was last updated
         *
         * @var int|null
         */
        public ?int $LastUpdated;

        /**
         * The Unix Timestamp for when this record was created
         *
         * @var int|null
         */
        public ?int $Created;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "id" => $this->ID,
                "hash" => $this->Hash,
                "image_type" => $this->ImageType,
                "safe_prediction" => $this->SafePrediction,
                "unsafe_prediction" => $this->UnsafePrediction,
                "is_nsfw" => $this->IsNSFW,
                "last_updated" => $this->LastUpdated,
                "created" => $this->Created
            ];
        }

        /**
         * Returns an Nsfw Classification Results object from this cache record.
         *
         * @return NsfwClassificationResults
         */
        public function toResults(): NsfwClassificationResults
        {
            $NsfwClassificationResultsObject = new NsfwClassificationResults();

            $NsfwClassificationResultsObject->ImageType = $this->ImageType;
            $NsfwClassificationResultsObject->SafePrediction = $this->SafePrediction;
            $NsfwClassificationResultsObject->UnsafePrediction = $this->UnsafePrediction;
            $NsfwClassificationResultsObject->IsNSFW = ($this->UnsafePrediction > $this->SafePrediction);
            $NsfwClassificationResultsObject->ContentHash = $this->Hash;

            return $NsfwClassificationResultsObject;
        }

        /**
         * Constructs an object from an array
         *
         * @param array $data
         * @return NsfwClassificationCache
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): NsfwClassificationCache
        {
            $NsfwClassificationCacheObject = new NsfwClassificationCache();

            if(isset($data["id"]))
                $NsfwClassificationCacheObject->ID = (int)$data["id"];

            if(isset($data["hash"]))
                $NsfwClassificationCacheObject->Hash = (string)$data["hash"];

            if(isset($data["image_type"]))
                $NsfwClassificationCacheObject->ImageType = (string)$data["image_type"];

            if(isset($data["safe_prediction"]))
                $NsfwClassificationCacheObject->SafePrediction = (float)$data["safe_prediction"];

            if(isset($data["unsafe_prediction"]))
                $NsfwClassificationCacheObject->UnsafePrediction = (float)$data["unsafe_prediction"];

            if(isset($data["is_nsfw"]))
                $NsfwClassificationCacheObject->IsNSFW = (bool)$data["is_nsfw"];

            if(isset($data["last_updated"]))
                $NsfwClassificationCacheObject->LastUpdated = (int)$data["last_updated"];

            if(isset($data["created"]))
                $NsfwClassificationCacheObject->Created = (int)$data["created"];

            return $NsfwClassificationCacheObject;
        }
    }