<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP;

    use CoffeeHouse\Objects\ProcessedNLP\NamedEntity;
    use CoffeeHouse\Objects\ProcessedNLP\Sentence;
    use CoffeeHouse\Objects\Results\CoreNLP\NamedEntitiesResults\NamedEntitySentence;

    /**
     * Class NamedEntitiesResults
     * @package CoffeeHouse\Objects\Results\CoreNLP
     */
    class NamedEntitiesResults
    {
        /**
         * @var string|null
         */
        public ?string $Text;

        /**
         * @var NamedEntitySentence[]|null
         */
        public ?array $NamedEntitySentences;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            $namedEntitySentences = [];

            foreach($this->NamedEntitySentences as $sentence)
                $namedEntitySentences[] = $sentence->toArray();

            return [
                "text" => $this->Text,
                "named_entity_sentences" => $namedEntitySentences
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return NamedEntitiesResults
         */
        public static function fromArray(array $data): NamedEntitiesResults
        {
            $NamedEntitiesResultsObject = new NamedEntitiesResults();

            if(isset($data["text"]))
                $NamedEntitiesResultsObject->Text = $data["text"];

            if(isset($data["named_entity_sentences"]))
            {
                $NamedEntitiesResultsObject->NamedEntitySentences = [];
                foreach($data["named_entity_sentences"] as $named_entity)
                    $NamedEntitiesResultsObject->NamedEntitySentences[] = NamedEntitySentence::fromArray($named_entity);
            }

            return $NamedEntitiesResultsObject;
        }


        /**
         * Constructs object from array
         *
         * @param string $text
         * @param Sentence[] $data
         * @return NamedEntitiesResults
         */
        public static function fromSentences(string $text, array $data): NamedEntitiesResults
        {
            $NamedEntitiesResultsObject = new NamedEntitiesResults();
            $NamedEntitiesResultsObject->Text = $text;
            $NamedEntitiesResultsObject->NamedEntitySentences = [];

            foreach($data as $sentence)
            {
                $NamedEntitySentence = new NamedEntitySentence();
                $NamedEntitySentence->Text = $sentence->Text;
                $NamedEntitySentence->OffsetBegin = $sentence->OffsetBegin;
                $NamedEntitySentence->OffsetEnd = $sentence->OffsetEnd;
                $NamedEntitySentence->NamedEntities = $sentence->NamedEntities;

                $NamedEntitiesResultsObject->NamedEntitySentences[] = $NamedEntitySentence;
            }

            return $NamedEntitiesResultsObject;
        }
    }