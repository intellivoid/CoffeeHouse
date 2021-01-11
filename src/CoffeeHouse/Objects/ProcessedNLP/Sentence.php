<?php


    namespace CoffeeHouse\Objects\ProcessedNLP;

    /**
     * Class Sentence
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class Sentence
    {
        /**
         * The tokenization of the sentence
         *
         * @var Token[]|null
         */
        public ?array $Tokens;

        /**
         * The sentimental prediction for this sentence
         *
         * @var Sentiment
         */
        public Sentiment $Sentiment;

        /**
         * An array of named entities that are detected in this sentence
         *
         * @var NamedEntity[]
         */
        public array $NamedEntities;

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
         * Sentence constructor.
         */
        public function __construct()
        {
            $this->OffsetBegin = null;
            $this->OffsetEnd = null;
            $this->Sentiment = new Sentiment();
            $this->Tokens = [];
            $this->NamedEntities = [];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return Sentence
         */
        public static function fromArray(array $data): Sentence
        {
            $SentenceObject = new Sentence();

            if(isset($data["tokens"]))
            {
                $SentenceObject->Tokens = [];
                foreach($data["tokens"] as $datum)
                    $SentenceObject->Tokens[] = Token::fromArray($datum);
            }

            if(isset($data["entitymentions"]))
            {
                $SentenceObject->NamedEntities = [];
                foreach($data["entitymentions"] as $datum)
                    $SentenceObject->NamedEntities[] = NamedEntity::fromArray($datum);
            }

            $SentenceObject->Sentiment = Sentiment::fromArray($data);

            $SentenceObject->OffsetBegin = $SentenceObject->Tokens[0]->CharacterOffsetBegin;
            $SentenceObject->OffsetEnd = $SentenceObject->Tokens[(count($SentenceObject->Tokens) - 1)]->CharacterOffsetEnd;

            return $SentenceObject;
        }
    }