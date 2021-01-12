<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP\SentimentResults;


    use CoffeeHouse\Objects\ProcessedNLP\Sentiment;

    class SentimentSentence
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
         * @var Sentiment|null
         */
        public ?Sentiment $Sentiment;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "sentiment" => $this->Sentiment->toArray()
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return SentimentSentence
         */
        public static function fromArray(array $data): SentimentSentence
        {
            $SentimentSentenceObject = new SentimentSentence();

            if(isset($data["text"]))
                $SentimentSentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $SentimentSentenceObject->OffsetBegin = (int)$data["offset_begin"];

            if(isset($data["offset_end"]))
                $SentimentSentenceObject->OffsetEnd = (int)$data["offset_end"];

            if(isset($data["sentiment"]))
                $SentimentSentenceObject->Sentiment = Sentiment::fromArray($data["sentiment"]);

            return $SentimentSentenceObject;
        }
    }