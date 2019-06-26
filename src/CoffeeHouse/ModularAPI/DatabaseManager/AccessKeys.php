<?php

    namespace ModularAPI\DatabaseManager;

    use ModularAPI\Abstracts\AccessKeySearchMethod;
    use ModularAPI\Exceptions\AccessKeyNotFoundException;
    use ModularAPI\Exceptions\DatabaseException;
    use ModularAPI\Exceptions\NoResultsFoundException;
    use ModularAPI\Exceptions\UnsupportedSearchMethodException;
    use ModularAPI\ModularAPI;
    use ModularAPI\Objects\AccessKey;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class AccessKeys
     * @package ModularAPI\DatabaseManager
     */
    class AccessKeys
    {
        /**
         * @var ModularAPI
         */
        private $modularAPI;

        /**
         * AccessKeys constructor.
         * @param ModularAPI $modularAPI
         */
        public function __construct(ModularAPI $modularAPI)
        {
            $this->modularAPI = $modularAPI;
        }

        /**
         * Registers the object to the database, returns the same object but with the ID Assigned
         *
         * @param AccessKey $accessKey
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function register(AccessKey $accessKey): AccessKey
        {
            $PublicID = $this->modularAPI->Database->real_escape_string($accessKey->PublicID);
            $PublicKey = $this->modularAPI->Database->real_escape_string($accessKey->PublicKey);
            $State = (int)$accessKey->State;
            $Usage = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Usage->toArray()));
            $Permissions = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Permissions->toArray()));
            $Analytics = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Analytics->toArray()));
            $Signatures = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Signatures->toArray()));
            $CreationDate = (int)$accessKey->CreationDate;

            $Query = "INSERT INTO `access_keys` (public_id, public_key, state, usage_data, permissions, analytics, signatures, creation_date) VALUES ('$PublicID', '$PublicKey', $State, '$Usage', '$Permissions', '$Analytics', '$Signatures', $CreationDate)";
            $QueryResults = $this->modularAPI->Database->query($Query);

            if($QueryResults == true)
            {
                return $this->get(AccessKeySearchMethod::byPublicID, $PublicID);
            }
            else
            {
                throw new DatabaseException($this->modularAPI->Database->error, $Query);
            }
        }

        /**
         * @param string|AccessKeySearchMethod $searchMethod
         * @param string $input
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         * @throws DatabaseException
         */
        public function get(string $searchMethod, string $input): AccessKey
        {
            switch($searchMethod)
            {
                case AccessKeySearchMethod::byPublicID:
                    $searchMethod = (string)$this->modularAPI->Database->real_escape_string($searchMethod);
                    $input = "'" . (string)$this->modularAPI->Database->real_escape_string($input) . "'";
                    break;

                case AccessKeySearchMethod::byPublicKey:
                    $searchMethod = (string)$this->modularAPI->Database->real_escape_string($searchMethod);
                    $input = "'" . (string)$this->modularAPI->Database->real_escape_string($input) . "'";
                    break;

                case AccessKeySearchMethod::byID:
                    $searchMethod = (string)$this->modularAPI->Database->real_escape_string($searchMethod);
                    $input = (int)$input;
                    break;

                case AccessKeySearchMethod::byCertificate:
                    $PublicKey = Hashing::calculatePublicKey($input);
                    $searchMethod = (string)$this->modularAPI->Database->real_escape_string(AccessKeySearchMethod::byPublicKey);
                    $input = "'" . (string)$this->modularAPI->Database->real_escape_string($PublicKey) . "'";
                    break;

                default:
                    throw new UnsupportedSearchMethodException();
            }

            $Query = "SELECT id, public_id, public_key, state, usage_data, permissions, analytics, signatures, creation_date FROM `access_keys` WHERE $searchMethod=$input";
            $QueryResults = $this->modularAPI->Database->query($Query);

            if($QueryResults == false)
            {
                throw new DatabaseException($this->modularAPI->Database->error, $Query);
            }
            else
            {
                if ($QueryResults->num_rows !== 1)
                {
                    throw new NoResultsFoundException();
                }

                $Row = $QueryResults->fetch_array(MYSQLI_ASSOC);

                $Row['usage'] = unserialize($Row['usage_data']);
                $Row['permissions'] = unserialize($Row['permissions']);
                $Row['analytics'] = unserialize($Row['analytics']);
                $Row['signatures'] = unserialize($Row['signatures']);

                return AccessKey::fromArray($Row);
            }
        }

        /**
         * Updates an existing Access Key in the Database
         *
         * @param AccessKey $accessKey
         * @return bool
         * @throws AccessKeyNotFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function update(AccessKey $accessKey): bool
        {
            try
            {
                $this->get(AccessKeySearchMethod::byID, $accessKey->ID);
            }
            catch(NoResultsFoundException $noResultsFoundException)
            {
                throw new AccessKeyNotFoundException();
            }

            $ID = (int)$accessKey->ID;
            $PublicID = $this->modularAPI->Database->real_escape_string($accessKey->PublicID);
            $PublicKey = $this->modularAPI->Database->real_escape_string($accessKey->PublicKey);
            $State = (int)$accessKey->State;
            $Usage = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Usage->toArray()));
            $Permissions = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Permissions->toArray()));
            $Analytics = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Analytics->toArray()));
            $Signatures = $this->modularAPI->Database->real_escape_string(serialize($accessKey->Signatures->toArray()));
            $CreationDate = (int)$accessKey->CreationDate;

            $Query = "UPDATE `access_keys` SET public_id='$PublicID', public_key='$PublicKey', state=$State, usage_data='$Usage', permissions='$Permissions', analytics='$Analytics', signatures='$Signatures', creation_date=$CreationDate WHERE id=$ID";
            $QueryResults = $this->modularAPI->Database->query($Query);

            if($QueryResults == true)
            {
                return true;
            }
            else
            {
                throw new DatabaseException($this->modularAPI->Database->error, $Query);
            }
        }
    }