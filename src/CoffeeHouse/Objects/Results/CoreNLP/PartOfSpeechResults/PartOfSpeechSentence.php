<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP\PartOfSpeechResults;


    use CoffeeHouse\Objects\ProcessedNLP\PosTag;

    /**
     * Class PartOfSpeechSentence
     * @package CoffeeHouse\Objects\Results\CoreNLP\SentenceSplitResults
     */
    class PartOfSpeechSentence
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
         * @var PosTag[]|null
         */
        public ?array $Tags;

        /**
         * @return array
         */
        public function toArray(): array
        {
            /** @var PosTag[] $tags_results */
            $tags_results = [];

            foreach($this->Tags as $posTag)
                $tags_results[] = $posTag->toArray();

            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "tags" => $tags_results
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return PartOfSpeechSentence
         */
        public static function fromArray(array $data): PartOfSpeechSentence
        {
            $PartOfSpeechSentenceObject = new PartOfSpeechSentence();

            if(isset($data["text"]))
                $PartOfSpeechSentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $PartOfSpeechSentenceObject->OffsetBegin = (int)$data["offset_begin"];

            if(isset($data["offset_end"]))
                $PartOfSpeechSentenceObject->OffsetEnd = (int)$data["offset_end"];

            if(isset($data["tags"]))
            {
                $PartOfSpeechSentenceObject->Tags = [];

                foreach($data["tags"] as $datum)
                    $PartOfSpeechSentenceObject->Tags[] = PosTag::fromArray($datum);
            }

            return $PartOfSpeechSentenceObject;
        }
    }