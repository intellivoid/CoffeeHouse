<?php

    namespace ModularAPI\DatabaseManager;
    use ModularAPI\Abstracts\AuthenticationType;
    use ModularAPI\Exceptions\DatabaseException;
    use ModularAPI\Exceptions\DatabaseNotEstablishedException;
    use ModularAPI\ModularAPI;
    use ModularAPI\Objects\ExceptionDetails;
    use ModularAPI\Objects\RequestAuthentication;
    use ModularAPI\Objects\RequestQuery;
    use ModularAPI\Objects\Response;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class Requests
     * @package ModularAPI\DatabaseManager
     */
    class Requests
    {
        /**
         * @var ModularAPI
         */
        private $modularAPI;

        /**
         * Requests constructor.
         * @param ModularAPI $modularAPI
         */
        public function __construct(ModularAPI $modularAPI)
        {
            $this->modularAPI = $modularAPI;
        }

        /**
         * Records the request to the database, returns reference ID
         *
         * @param string $client_ip
         * @param float $executionTime
         * @param RequestQuery $requestQuery
         * @param array $parameters
         * @param Response $response
         * @param RequestAuthentication $requestAuthentication
         * @param string $access_key_public_id
         * @param bool $fatalError
         * @param ExceptionDetails|null $exceptionDetails
         * @return string
         * @throws DatabaseNotEstablishedException
         */
        public function recordRequest(string $client_ip, float $executionTime, RequestQuery $requestQuery, array $parameters, Response $response, RequestAuthentication $requestAuthentication, string $access_key_public_id, bool $fatalError, ExceptionDetails $exceptionDetails = null): string
        {
            if($this->modularAPI->Database == null)
            {
                throw new DatabaseNotEstablishedException();
            }

            $Timestamp = (int)time();
            $ExecutionTime = (float)$executionTime;
            $ReferenceID = Hashing::calculateReferenceID($Timestamp, $requestQuery->Version, $requestQuery->Module, $client_ip);
            $ReferenceID = $this->modularAPI->Database->real_escape_string($ReferenceID);
            $ClientIP = $this->modularAPI->Database->real_escape_string($client_ip);
            $Version = $this->modularAPI->Database->real_escape_string($requestQuery->Version);
            $Module = $this->modularAPI->Database->real_escape_string($requestQuery->Module);
            $RequestMethod = $this->modularAPI->Database->real_escape_string($requestQuery->RequestMethod);
            $RequestParameters = $this->modularAPI->Database->real_escape_string(json_encode($parameters));
            $ResponseType = $this->modularAPI->Database->real_escape_string($response->ResponseType);
            $ResponseCode = (int)$response->ResponseCode;
            $AuthenticationMethod = $this->modularAPI->Database->real_escape_string($requestAuthentication->Type);
            $AccessKeyPublicID = 'NONE';
            if($requestAuthentication->Type !== AuthenticationType::None)
            {
                $AccessKeyPublicID = $this->modularAPI->Database->real_escape_string($access_key_public_id);
            }
            $FatalError = (int)$fatalError;
            $ExceptionDetails = 'NONE';
            if($exceptionDetails !== null)
            {
                $ExceptionDetails = $this->modularAPI->Database->real_escape_string(json_encode($exceptionDetails->toArray()));
            }

            $Query = "INSERT INTO `requests` (reference_id, execution_time, timestamp, client_ip, version, module, request_method, request_parameters, response_type, response_code, authentication_method, access_key_public_id, fatal_error, exception_details) VALUES ('$ReferenceID', $ExecutionTime, $Timestamp, '$ClientIP', '$Version', '$Module', '$RequestMethod', '$RequestParameters', '$ResponseType', $ResponseCode, '$AuthenticationMethod', '$AccessKeyPublicID', $FatalError, '$ExceptionDetails')";
            $QueryResults = $this->modularAPI->Database->query($Query);

            if($QueryResults == true)
            {
                return $ReferenceID;
            }
            else
            {
                throw new DatabaseException($this->modularAPI->Database->error, $Query);
            }
        }
    }