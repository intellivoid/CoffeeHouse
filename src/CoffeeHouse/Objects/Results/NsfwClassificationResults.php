<?php /** @noinspection PhpMissingFieldTypeInspection */


    namespace CoffeeHouse\Objects\Results;

    use CoffeeHouse\Abstracts\ImageType;

    /**
     * Class NsfwClassificationResults
     * @package CoffeeHouse\Objects\Results
     */
    class NsfwClassificationResults
    {
        /**
         * The image type that was processed
         *
         * @var string|ImageType|null
         */
        public $ImageType;

        /**
         * The content hash SHA256
         *
         * @var string|null
         */
        public $ContentHash;

        /**
         * The safe prediction value
         *
         * @var float|int|null
         */
        public $SafePrediction;

        /**
         * The unsafe prediction value
         *
         * @var float|int|null
         */
        public $UnsafePrediction;

        /**
         * Indicates if this image is NSFW or not
         *
         * @var bool|null
         */
        public $IsNSFW;

        /**
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $this->IsNSFW = $this->UnsafePrediction > $this->SafePrediction;

            return [
                "image_type" => $this->ImageType,
                "content_hash" => $this->ContentHash,
                "safe_prediction" => (float)$this->SafePrediction,
                "unsafe_prediction" => (float)$this->UnsafePrediction,
                "is_nsfw" => (bool)$this->IsNSFW
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return NsfwClassificationResults
         * @noinspection PhpPureAttributeCanBeAddedInspection
         * @noinspection DuplicatedCode
         */
        public static function fromArray(array $data): NsfwClassificationResults
        {
            $NsfwClassificationResultsObject = new NsfwClassificationResults();

            if(isset($data["image_type"]))
                $NsfwClassificationResultsObject->ImageType = $data["image_type"];

            if(isset($data["content_hash"]))
                $NsfwClassificationResultsObject->ContentHash = $data["content_hash"];

            if(isset($data["safe_prediction"]))
                $NsfwClassificationResultsObject->SafePrediction = (float)$data["safe_prediction"];

            if(isset($data["unsafe_prediction"]))
                $NsfwClassificationResultsObject->UnsafePrediction = (float)$data["unsafe_prediction"];

            if(isset($data["is_nsfw"]))
                $NsfwClassificationResultsObject->IsNSFW = (bool)$data["is_nsfw"];

            return $NsfwClassificationResultsObject;
        }
    }