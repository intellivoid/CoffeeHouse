<?php


    namespace CoffeeHouse\Objects\Results\CoreNLP\NamedEntitiesResults;


    use CoffeeHouse\Objects\ProcessedNLP\NamedEntity;

    /**
     * Class NamedEntitySentence
     * @package CoffeeHouse\Objects\Results\CoreNLP\NamedEntitiesResults
     */
    class NamedEntitySentence
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
         * The named entities related to this sentence
         *
         * @var NamedEntity[]|null
         */
        public ?array $NamedEntities;

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
         */
        public function toArray(): array
        {
            $named_entities = [];
            foreach($this->NamedEntities as $namedEntity)
                $named_entities[] = $namedEntity->toArray();

            return [
                "text" => $this->Text,
                "offset_begin" => $this->OffsetBegin,
                "offset_end" => $this->OffsetEnd,
                "named_entities" => $named_entities
            ];
        }

        /**
         * Constructs object from array
         *
         * @param array $data
         * @return NamedEntitySentence
         */
        public static function fromArray(array $data): NamedEntitySentence
        {
            $NamedEntitySentenceObject = new NamedEntitySentence();

            if(isset($data["text"]))
                $NamedEntitySentenceObject->Text = $data["text"];

            if(isset($data["offset_begin"]))
                $NamedEntitySentenceObject->OffsetBegin = $data["offset_begin"];

            if(isset($data["offset_end"]))
                $NamedEntitySentenceObject->OffsetEnd = $data["offset_end"];

            if(isset($data["named_entities"]))
            {
                $NamedEntitySentenceObject->NamedEntities = [];

                foreach($data["named_entities"] as $named_entity)
                    $NamedEntitySentenceObject->NamedEntities[] = NamedEntity::fromArray($named_entity);
            }

            return $NamedEntitySentenceObject;
        }
    }