<?php


    namespace CoffeeHouse\Objects\ProcessedNLP;

    /**
     * Class PartOfSpeechSentence
     * @package CoffeeHouse\Objects\ProcessedNLP
     */
    class Sentence
    {
        /**
         * The text that the sentence is based off
         *
         * @var string|null
         */
        public ?string $Text;

        /**
         * The tokenization of the sentence
         *
         * @var Token[]|null
         */
        public ?array $Tokens;

        /**
         * The detected part of speech variables
         *
         * @var PosTag[]|null
         */
        public ?array $PartOfSpeechTags;

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
         * PartOfSpeechSentence constructor.
         */
        public function __construct()
        {
            $this->Text = null;
            $this->OffsetBegin = null;
            $this->OffsetEnd = null;
            $this->Sentiment = new Sentiment();
            $this->Tokens = [];
            $this->PartOfSpeechTags = [];
            $this->NamedEntities = [];
        }

        /**
         * Constructs object from array
         *
         * @param string $input
         * @param array $data
         * @return Sentence
         */
        public static function fromArray(string $input, array $data): Sentence
        {
            $SentenceObject = new Sentence();

            if(isset($data["tokens"]))
            {
                $SentenceObject->Tokens = [];
                $SentenceObject->PartOfSpeechTags = [];

                foreach($data["tokens"] as $datum)
                {
                    $SentenceObject->Tokens[] = Token::fromArray($datum);
                    $SentenceObject->PartOfSpeechTags[] = PosTag::fromArray($datum);
                }
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
            $SentenceObject->Text = substr(
                $input,
                $SentenceObject->OffsetBegin,
                /**
                 * The difference between substr in Java and PHP is that PHP
                 * counts from the offset begin for the length. For example
                 *
                 * Hello World! (2, 5)
                 *  ^---^ (Java)
                 *
                 * But PHP does this
                 *
                 * Hello World!
                 *  ^-----^ (PHP)
                 *
                 * So to mitigate this, the length must be a value subtracted from the offset.
                 */
                ($SentenceObject->OffsetEnd - $SentenceObject->OffsetBegin))
            ;

            return $SentenceObject;
        }
    }