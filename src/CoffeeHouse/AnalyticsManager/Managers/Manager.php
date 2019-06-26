<?php

    namespace AnalyticsManager\Managers;

    use AnalyticsManager\Abstracts\RecordSearchMethod;
    use AnalyticsManager\AnalyticsManager;
    use AnalyticsManager\Exceptions\DatabaseException;
    use AnalyticsManager\Exceptions\RecordAlreadyExistsException;
    use AnalyticsManager\Exceptions\RecordNotFoundException;
    use AnalyticsManager\Objects\DayData;
    use AnalyticsManager\Objects\MonthData;
    use AnalyticsManager\Objects\Record;
    use AnalyticsManager\Utilities\Builder;
    use AnalyticsManager\Utilities\Hashing;
    use ZiProto\ZiProto;

    /**
     * Class Manager
     * @package AnalyticsManager\Managers
     */
    class Manager
    {
        /**
         * @var AnalyticsManager
         */
        private $analyticsManager;

        /**
         * Manager constructor.
         * @param AnalyticsManager $analyticsManager
         */
        public function __construct(AnalyticsManager $analyticsManager)
        {
            $this->analyticsManager = $analyticsManager;
        }

        /**
         * Creates a new record in the Database
         *
         * @param string $table
         * @param string $name
         * @return Record
         * @throws DatabaseException
         * @throws RecordAlreadyExistsException
         * @throws RecordNotFoundException
         * @throws RecordNotFoundException
         */
        public function createRecord(string $table, string $name): Record
        {
            if($this->nameExists($table, $name) == true)
            {
                throw new RecordAlreadyExistsException();
            }

            $CreationTimestamp = (int)time();
            $PublicID = $this->analyticsManager->getDatabase()->real_escape_string(Hashing::recordPublicID($name, $CreationTimestamp));
            $Name = $this->analyticsManager->getDatabase()->real_escape_string($name);

            $ThisMonth = new MonthData();
            $ThisMonth->available = true;
            $ThisMonth->data = Builder::buildMonth((int)date('n'), (int)date('Y'));
            $ThisMonth->month_date = (int)date('n');

            $LastMonth = new MonthData();
            $LastMonth->available = false;
            $LastMonth->data = array();
            $LastMonth->month_date = 0;

            $Today = new DayData();
            $Today->available = true;
            $Today->data = Builder::buildDay();
            $Today->day_date = (int)date('j');

            $Yesterday = new DayData();
            $Yesterday->available = false;
            $Yesterday->data = array();

            $LastUpdated = $CreationTimestamp;

            $ThisMonth = ZiProto::encode($ThisMonth->toArray());
            $LastMonth = ZiProto::encode($LastMonth->toArray());
            $Today = ZiProto::encode($Today->toArray());
            $Yesterday = ZiProto::encode($Yesterday->toArray());

            $table = $this->analyticsManager->getDatabase()->real_escape_string($table);

            /** @noinspection SqlResolve */
            $Query = "INSERT INTO `$table` (public_id, name, this_month, last_month, today, yesterday, creation_timestamp, last_updated) VALUES ('$PublicID', '$Name', '$ThisMonth', '$LastMonth', '$Today', '$Yesterday', $CreationTimestamp, $LastUpdated)";
            $QueryResults = $this->analyticsManager->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($this->analyticsManager->getDatabase()->error, $Query);
            }
            else
            {
                return $this->getRecord($table, RecordSearchMethod::byPublicId, $PublicID);
            }
        }

        /**
         * Gets an existing record from the Database
         *
         * @param string $table
         * @param string $search_method
         * @param string $value
         * @return Record
         * @throws DatabaseException
         * @throws RecordNotFoundException
         */
        public function getRecord(string $table, string $search_method, string $value): Record
        {
            switch($search_method)
            {
                case RecordSearchMethod::byId:
                    $search_method = $this->analyticsManager->getDatabase()->real_escape_string($search_method);
                    $value = (int)$value;
                    break;

                case RecordSearchMethod::byPublicId:
                    $search_method = $this->analyticsManager->getDatabase()->real_escape_string($search_method);
                    $value = "'" . $this->analyticsManager->getDatabase()->real_escape_string($value) . "'";
                    break;

                case RecordSearchMethod::byName:
                    $search_method = $this->analyticsManager->getDatabase()->real_escape_string($search_method);
                    $value = "'" . $this->analyticsManager->getDatabase()->real_escape_string($value) . "'";
                    break;
            }

            $table = $this->analyticsManager->getDatabase()->real_escape_string($table);

            /** @noinspection SqlResolve */
            $Query = "SELECT id, public_id, name, this_month, last_month, today, yesterday, creation_timestamp, last_updated FROM `$table` WHERE $search_method=$value";
            $QueryResults = $this->analyticsManager->getDatabase()->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($this->analyticsManager->getDatabase()->error, $Query);
            }
            else
            {
                if($QueryResults->num_rows !== 1)
                {
                    throw new RecordNotFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                $Row['this_month'] = ZiProto::decode($Row['this_month']);
                $Row['last_month'] = ZiProto::decode($Row['last_month']);
                $Row['today'] = ZiProto::decode($Row['today']);
                $Row['yesterday'] = ZiProto::decode($Row['yesterday']);

                return Record::fromArray($Row);
            }
        }

        /**
         * Updates an existing record
         *
         * @param string $table
         * @param Record $record
         * @return bool
         * @throws DatabaseException
         * @throws RecordNotFoundException
         */
        public function updateRecord(string $table, Record $record): bool
        {
            if($this->idExists($table, $record->ID) == false)
            {
                throw new RecordNotFoundException();
            }

            $ID = (int)$record->ID;
            $PublicID = $this->analyticsManager->getDatabase()->real_escape_string($record->PublicID);
            $Name = $this->analyticsManager->getDatabase()->real_escape_string($record->Name);
            $ThisMonth = $this->analyticsManager->getDatabase()->real_escape_string(ZiProto::encode($record->ThisMonth->toArray()));
            $LastMonth = $this->analyticsManager->getDatabase()->real_escape_string(ZiProto::encode($record->LastMonth->toArray()));
            $Today = $this->analyticsManager->getDatabase()->real_escape_string(ZiProto::encode($record->Today->toArray()));
            $Yesterday = $this->analyticsManager->getDatabase()->real_escape_string(ZiProto::encode($record->Yesterday->toArray()));
            $CreationTimestamp = (int)$record->CreationTimestamp;
            $LastUpdated = (int)time();

            /** @noinspection SqlResolve */
            $Query = "UPDATE `$table` SET public_id='$PublicID', name='$Name', this_month='$ThisMonth', last_month='$LastMonth', today='$Today', yesterday='$Yesterday', creation_timestamp=$CreationTimestamp, last_updated=$LastUpdated WHERE id=$ID";
            $QueryResults = $this->analyticsManager->getDatabase()->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($this->analyticsManager->getDatabase()->error, $Query);
            }
        }

        /**
         * Determines if the ID exists in the database
         *
         * @param string $table
         * @param int $id
         * @return bool
         * @throws DatabaseException
         * @throws DatabaseException
         */
        public function idExists(string $table, int $id): bool
        {
            try
            {
                $this->getRecord($table, RecordSearchMethod::byId, $id);
                return true;
            }
            catch(RecordNotFoundException $recordNotFoundException)
            {
                return false;
            }
        }

        /**
         * Determines if the Public ID exists in the database
         *
         * @param string $table
         * @param string $public_id
         * @return bool
         * @throws DatabaseException
         * @throws DatabaseException
         */
        public function publicIdExists(string $table, string $public_id): bool
        {
            try
            {
                $this->getRecord($table, RecordSearchMethod::byPublicId, $public_id);
                return true;
            }
            catch(RecordNotFoundException $recordNotFoundException)
            {
                return false;
            }
        }

        /**
         * Determines if the name exists in the database
         *
         * @param string $table
         * @param string $name
         * @return bool
         * @throws DatabaseException
         * @throws DatabaseException
         */
        public function nameExists(string $table, string $name): bool
        {
            try
            {
                $this->getRecord($table, RecordSearchMethod::byName, $name);
                return true;
            }
            catch(RecordNotFoundException $recordNotFoundException)
            {
                return false;
            }
        }
    }