<?php


    namespace CoffeeHouse\Managers;

    use CoffeeHouse\Abstracts\LocalSessionSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\LocalSessionNotFoundException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Objects\ForeignSession;
    use CoffeeHouse\Objects\LocalSession;
    use msqg\QueryBuilder;

    /**
     * Class LocalSessionManager
     * @package CoffeeHouse\Managers
     */
    class LocalSessionManager
    {
        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * LocalSessionManager constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Creates a new local session associated with this foreign session.
         *
         * @param ForeignSession $foreignSession
         * @return LocalSession
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws LocalSessionNotFoundException
         * @throws NoResultsFoundException
         */
        public function createLocalSession(ForeignSession $foreignSession): LocalSession
        {
            try
            {
                return $this->getLocalSession(LocalSessionSearchMethod::ByForeignSessionId, $foreignSession->ID);
            }
            catch(LocalSessionNotFoundException $e)
            {
                unset($e);
            }

            $foreign_session_id = (int)$foreignSession->ID;

            /** @noinspection PhpUnhandledExceptionInspection */
            // Language requires more because it uses predictions from both the AI and User.
            $language_large_generalization = $this->coffeeHouse->getLargeGeneralizedClassificationManager()->create(20);
            $language_large_generalization_id = (int)$language_large_generalization->ID;

            /** @noinspection PhpUnhandledExceptionInspection */
            $ai_emotions_large_generalization = $this->coffeeHouse->getLargeGeneralizedClassificationManager()->create(10);
            $ai_emotions_large_generalization_id = (int)$ai_emotions_large_generalization->ID;

            $created_timestamp = (int)time();
            $last_updated_timestamp = $created_timestamp;

            $Query = QueryBuilder::insert_into("local_sessions", array(
                "foreign_session_id" => $foreign_session_id,
                "language_large_generalization_id" => $language_large_generalization_id,
                "predicted_language" => null,
                "ai_emotions_large_generalization_id" => $ai_emotions_large_generalization_id,
                "ai_current_emotion" => null,
                "created_timestamp" => $created_timestamp,
                "last_updated_timestamp" => $last_updated_timestamp
            ));

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                return $this->getLocalSession(LocalSessionSearchMethod::ByForeignSessionId, $foreign_session_id);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);

            }
        }

        /**
         * Returns a local session object from the Database
         *
         * @param string $search_method
         * @param string $value
         * @return LocalSession
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws LocalSessionNotFoundException
         */
        public function getLocalSession(string $search_method, string $value): LocalSession
        {
            switch($search_method)
            {
                case LocalSessionSearchMethod::ById:
                case LocalSessionSearchMethod::ByForeignSessionId:
                    $search_method = $this->coffeeHouse->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                default:
                    throw new InvalidSearchMethodException();
            }

            $Query = QueryBuilder::select("local_sessions", [
                "id",
                "foreign_session_id",
                "language_large_generalization_id",
                "predicted_language",
                "ai_emotions_large_generalization_id",
                "ai_current_emotion",
                "created_timestamp",
                "last_updated_timestamp"
            ], $search_method, $value, null, null, 1);
            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                if ($Row == False)
                {
                    throw new LocalSessionNotFoundException();
                }
                else
                {
                    return(LocalSession::fromArray($Row));
                }
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }

        /**
         * Updates a local session object
         *
         * @param LocalSession $localSession
         * @return bool
         * @throws DatabaseException
         */
        public function updateLocalSession(LocalSession $localSession): bool
        {
            $id = (int)$localSession->ID;

            $predicted_language = null;
            $ai_current_emotion = null;

            if($localSession->PredictedLanguage !== null)
                $predicted_language = $this->coffeeHouse->getDatabase()->real_escape_string($localSession->PredictedLanguage);

            if($localSession->AiCurrentEmotion !== null)
                $ai_current_emotion = $this->coffeeHouse->getDatabase()->real_escape_string($localSession->AiCurrentEmotion);

            $Query = QueryBuilder::update('local_sessions', array(
                "language_large_generalization_id" => (int)$localSession->LanguageLargeGeneralizationID,
                "predicted_language" => $predicted_language,
                "ai_emotions_large_generalization_id" => (int)$localSession->AiEmotionsLargeGeneralizationID,
                "ai_current_emotion" => $ai_current_emotion,
                "last_updated_timestamp" => (int)time()
            ), "id", $id);

            $QueryResults = $this->coffeeHouse->getDatabase()->query($Query);

            if($QueryResults)
            {
                return(True);
            }
            else
            {
                throw new DatabaseException($this->coffeeHouse->getDatabase()->error);
            }
        }
    }