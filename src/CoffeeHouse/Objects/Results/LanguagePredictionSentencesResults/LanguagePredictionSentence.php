<?php


    namespace CoffeeHouse\Objects\Results\LanguagePredictionSentencesResults;


    use CoffeeHouse\Exceptions\MalformedDataException;
    use CoffeeHouse\Objects\Results\LanguagePredictionResults;

    /**
     * Class LanguagePredictionSentence
     * @package CoffeeHouse\Objects\Results\LanguagePredictionSentencesResults
     */
    class LanguagePredictionSentence
    {
        /**
         * The text of the sentence
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * The offset beginning of this sentence
         *
         * @var int|null
         */
        public ?int $OffsetBegin;

        /**
         * The offset end of this sentence
         *
         * @var int|null
         */
        public ?int $OffsetEnd;

        /**
         * The Spam Prediction Results for this sentence
         *
         * @var LanguagePredictionResults|null
         */
        public ?LanguagePredictionResults $LanguagePredictionResults;

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "language_prediction" => $this->LanguagePredictionResults->toArray()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return LanguagePredictionSentence
         * @throws MalformedDataException
         */
        public static function fromArray(array $data): LanguagePredictionSentence
        {
            $LanguagePredictionSentenceObject = new LanguagePredictionSentence();

            if(isset($data["text"]))
                $LanguagePredictionSentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $LanguagePredictionSentenceObject->OffsetBegin = (int)$data["offset_begin"];

            if(isset($data["offset_end"]))
                $LanguagePredictionSentenceObject->OffsetEnd = (int)$data["offset_end"];

            if(isset($data["language_prediction"]))
                $LanguagePredictionSentenceObject->LanguagePredictionResults = LanguagePredictionResults::fromArray($data["language_prediction"]);

            return $LanguagePredictionSentenceObject;
        }
    }