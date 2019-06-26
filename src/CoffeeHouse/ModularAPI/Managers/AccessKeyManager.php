<?php

    namespace ModularAPI\Managers;

    use Exception;
    use ModularAPI\Abstracts\AccessKeySearchMethod;
    use ModularAPI\Abstracts\AccessKeyStatus;
    use ModularAPI\DatabaseManager\AccessKeys;
    use ModularAPI\Exceptions\AccessKeyExpiredException;
    use ModularAPI\Exceptions\AccessKeyNotFoundException;
    use ModularAPI\Exceptions\InvalidAccessKeyStatusException;
    use ModularAPI\Exceptions\NoResultsFoundException;
    use ModularAPI\Exceptions\UnsupportedSearchMethodException;
    use ModularAPI\Exceptions\UsageExceededException;
    use ModularAPI\ModularAPI;
    use ModularAPI\Objects\AccessKey;
    use ModularAPI\Utilities\Builder;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class AccessKeyManager
     * @package ModularAPI\Managers
     */
    class AccessKeyManager
    {
        /**
         * @var ModularAPI
         */
        private $modularAPI;

        /**
         * Manages objects in the Database
         *
         * @var AccessKeys
         */
        public $Manager;

        /**
         * AccessKeyManager constructor.
         * @param ModularAPI $modularAPI
         */
        public function __construct(ModularAPI $modularAPI)
        {
            $this->modularAPI = $modularAPI;
            $this->Manager = new AccessKeys($modularAPI);
        }

        /**
         *  Registers a new Access Key to the Database
         *
         * @param array $usageConfiguration
         * @param array $permissionsConfiguration
         * @param int|AccessKeyStatus $startingState
         * @return AccessKey
         * @throws InvalidAccessKeyStatusException
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         * @throws Exception
         */
        public function createKey(array $usageConfiguration, array $permissionsConfiguration, int $startingState = 0): AccessKey
        {
            $AccessKeyObject = new AccessKey();
            $AccessKeyObject->Analytics = new AccessKey\Analytics();
            $AccessKeyObject->Permissions = new AccessKey\Permissions();
            $AccessKeyObject->Signatures = new AccessKey\Signatures();
            $AccessKeyObject->Usage = new AccessKey\Usage();

            $IssuerName = $this->modularAPI->getModularApiConfiguration()['IssuerName'];

            // Build the signatures
            $CurrentTime = time();
            $AccessKeyObject->Signatures->IssuerName = $IssuerName;

            $AccessKeyObject->Signatures->TimeSignature = Hashing::generateTimeSignature(
                $CurrentTime,
                $AccessKeyObject->Signatures->IssuerName
            );

            $AccessKeyObject->Signatures->PrivateSignature = Hashing::generatePrivateSignature(
                $AccessKeyObject->Signatures->TimeSignature,
                $AccessKeyObject->Signatures->IssuerName,
                $CurrentTime
            );

            $AccessKeyObject->Signatures->PublicSignature = Hashing::generatePublicSignature(
                $AccessKeyObject->Signatures->TimeSignature,
                $AccessKeyObject->Signatures->PrivateSignature
            );

            // Fill out the rest of the properties
            $AccessKeyObject->PublicKey = Hashing::calculatePublicKey($AccessKeyObject->Signatures->createCertificate());
            $AccessKeyObject->PublicID = Hashing::calculatePublicID(
                $AccessKeyObject->Signatures->PrivateSignature,
                $AccessKeyObject->Signatures->PublicSignature,
                $AccessKeyObject->Signatures->TimeSignature
            );

            // Build configuration
            $AccessKeyObject->Usage->loadConfiguration($usageConfiguration);
            $AccessKeyObject->Permissions->loadConfiguration($permissionsConfiguration);

            $AccessKeyObject->Analytics->LastMonthAvailable = false;
            $AccessKeyObject->Analytics->LastMonthID = null;
            $AccessKeyObject->Analytics->LastMonthUsage = [];

            $AccessKeyObject->Analytics->CurrentMonthAvailable = true;
            $AccessKeyObject->Analytics->CurrentMonthID = Hashing::calculateMonthID((int)date('n'), (int)date('Y'));
            $AccessKeyObject->Analytics->CurrentMonthUsage = Builder::createMonthArray();

            switch($startingState)
            {
                case AccessKeyStatus::Activated:
                    $AccessKeyObject->State = AccessKeyStatus::Activated;
                    break;

                case AccessKeyStatus::Suspended:
                    $AccessKeyObject->State = AccessKeyStatus::Suspended;
                    break;

                case AccessKeyStatus::Limited:
                    $AccessKeyObject->State = AccessKeyStatus::Limited;
                    break;

                default:
                    throw new InvalidAccessKeyStatusException();
            }

            $AccessKeyObject->CreationDate = time();

            return $this->Manager->register($AccessKeyObject);
        }

        /**
         * Verifies the certificate and returns the AccessKey Object
         *
         * @param string $certificate
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function verifyCertificate(string $certificate): AccessKey
        {
            return $this->Manager->get(AccessKeySearchMethod::byCertificate, $certificate);
        }

        /**
         * Verifies the access key and returns the AccessKey Object
         *
         * @param string $api_key
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function verifyAPIKey(string $api_key): AccessKey
        {
            return $this->Manager->get(AccessKeySearchMethod::byPublicKey, $api_key);
        }

        /**
         * Gets the Access Key via Public ID
         *
         * @param string $public_id
         * @return AccessKey
         * @throws NoResultsFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function getAccessKey(string $public_id): AccessKey
        {
            return $this->Manager->get(AccessKeySearchMethod::byPublicID, $public_id);
        }

        /**
         * Tracks the usage, throws an exception if usage limit exceeded or the access key has expired
         *
         * @param AccessKey $accessKey
         * @param bool $trackExceeding
         * @return bool
         * @throws UsageExceededException
         * @throws AccessKeyExpiredException
         * @throws AccessKeyNotFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function trackUsage(AccessKey $accessKey, bool $trackExceeding): bool
        {
            $accessKey->Usage->trackUsage($trackExceeding);
            $accessKey->Analytics->trackUsage();
            $this->Manager->update($accessKey);

            return true;
        }

        /**
         * Changes the access key's signatures to new signatures
         *
         * @param AccessKey $accessKey
         * @throws AccessKeyNotFoundException
         * @throws UnsupportedSearchMethodException
         */
        public function changeSignatures(AccessKey $accessKey)
        {
            $CurrentTime = time();

            $accessKey->Signatures->TimeSignature = Hashing::generateTimeSignature(
                $CurrentTime,
                $accessKey->Signatures->IssuerName
            );
            $accessKey->Signatures->PrivateSignature = Hashing::generatePrivateSignature(
                $accessKey->Signatures->TimeSignature,
                $accessKey->Signatures->IssuerName,
                $CurrentTime
            );
            $accessKey->Signatures->PublicSignature = Hashing::generatePublicSignature(
                $accessKey->Signatures->TimeSignature,
                $accessKey->Signatures->PrivateSignature
            );

            $accessKey->PublicKey = $accessKey->Signatures->createCertificate();
            $this->Manager->update($accessKey);
        }
    }