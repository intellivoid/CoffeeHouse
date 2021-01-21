<?php


    namespace CoffeeHouse\Objects;

    use CoffeeHouse\Abstracts\LargeGeneralizedClassificationSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use JetBrains\PhpStorm\Pure;

    /**
     * Class LocalSession
     * @package CoffeeHouse\Objects
     */
    class LocalSession
    {
        /**
         * The Unique Internal Database ID
         *
         * @var int|null
         */
        public ?int $ID;

        /**
         * The Foreigin Session ID related to this local session
         *
         * @var int|null
         */
        public ?int $ForeignSessionID;

        /**
         * The Large Generalization ID for the language prediction data
         *
         * @var int|null
         */
        public ?int $LanguageLargeGeneralizationID;

        /**
         * The large generalization object for the language, use initialize() to initialize.
         *
         * @var LargeGeneralization|null
         */
        public ?LargeGeneralization $LanguageLargeGeneralization;

        /**
         * The predicted language for the session overall
         *
         * @var string|null
         */
        public ?string $PredictedLanguage;

        /**
         * The Large Generalization ID for the emotion data
         *
         * @var int|null
         */
        public ?int $AiEmotionsLargeGeneralizationID;

        /**
         * The large generalization object for emotions, use initialize() to initialize.
         *
         * @var LargeGeneralization|null
         */
        public ?LargeGeneralization $EmotionLargeGeneralization;

        /**
         * The AI's current emotion for this session
         *
         * @var string|null
         */
        public ?string $AiCurrentEmotion;

        /**
         * The Unix Timestamp for when this record was created
         *
         * @var int|null
         */
        public ?int $CreatedTimestamp;

        /**
         * The Unix Timestamp for when this record was last updated
         *
         * @var int|null
         */
        public ?int $LastUpdatedTimestamp;

        /**
         * LocalSession constructor.
         */
        public function __construct()
        {
            $this->ID = null;
            $this->ForeignSessionID = null;
            $this->LanguageLargeGeneralizationID = null;
            $this->LanguageLargeGeneralization = null;
            $this->PredictedLanguage = null;
            $this->AiEmotionsLargeGeneralizationID = null;
            $this->EmotionLargeGeneralization = null;
            $this->AiCurrentEmotion = null;
            $this->CreatedTimestamp = null;
            $this->LastUpdatedTimestamp = null;
        }

        /**
         * Initializes the large generalization objects, requires CoffeeHouse.
         *
         * @param CoffeeHouse $coffeeHouse
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         */
        public function initialize(CoffeeHouse $coffeeHouse)
        {
            if($this->LanguageLargeGeneralization == null)
            {
                $this->LanguageLargeGeneralization = $coffeeHouse->getLargeGeneralizedClassificationManager()->get(
                    LargeGeneralizedClassificationSearchMethod::byID, $this->LanguageLargeGeneralizationID
                );
            }

            if($this->EmotionLargeGeneralization == null)
            {
                $this->EmotionLargeGeneralization = $coffeeHouse->getLargeGeneralizedClassificationManager()->get(
                    LargeGeneralizedClassificationSearchMethod::byID, $this->AiEmotionsLargeGeneralizationID
                );
            }
        }

        /**
         * Returns an array representation of this object
         *
         * @return array
         * @noinspection PhpArrayShapeAttributeCanBeAddedInspection Not compatible with PHP 7 and below
         */
        public function toArray(): array
        {
            return [
                "id" => $this->ID,
                "foreign_session_id" => $this->ForeignSessionID,
                "language_large_generalization_id" => $this->LanguageLargeGeneralizationID,
                "predicted_language" => $this->PredictedLanguage,
                "ai_emotions_large_generalization_id" => $this->AiEmotionsLargeGeneralizationID,
                "ai_current_emotion" => $this->AiCurrentEmotion,
                "created_timestamp" => $this->CreatedTimestamp,
                "last_updated_timestamp" => $this->LastUpdatedTimestamp
            ];
        }

        /**
         * Constructs the object from an array
         *
         * @param array $data
         * @return LocalSession
         */
        public static function fromArray(array $data): LocalSession
        {
            $LocalSessionObject = new LocalSession();

            if(isset($data["id"]))
                $LocalSessionObject->ID = (int)$data["id"];

            if(isset($data["foreign_session_id"]))
                $LocalSessionObject->ForeignSessionID = (int)$data["foreign_session_id"];

            if(isset($data["language_large_generalization_id"]))
                $LocalSessionObject->LanguageLargeGeneralizationID = (int)$data["language_large_generalization_id"];

            if(isset($data["predicted_language"]))
            {
                if($data["predicted_language"] !== null)
                    $LocalSessionObject->PredictedLanguage = (string)$data["predicted_language"];
            }

            if(isset($data["ai_emotions_large_generalization_id"]))
                $LocalSessionObject->AiEmotionsLargeGeneralizationID = (int)$data["ai_emotions_large_generalization_id"];

            if(isset($data["ai_current_emotion"]))
            {
                if($data["ai_current_emotion"] !== null)
                    $LocalSessionObject->AiCurrentEmotion = (string)$data["ai_current_emotion"];
            }

            if(isset($data["created_timestamp"]))
                $LocalSessionObject->CreatedTimestamp = (int)$data["created_timestamp"];

            if(isset($data["last_updated_timestamp"]))
                $LocalSessionObject->LastUpdatedTimestamp = (int)$data["last_updated_timestamp"];

            return $LocalSessionObject;
        }
    }