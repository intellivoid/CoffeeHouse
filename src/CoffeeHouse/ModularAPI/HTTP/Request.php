<?php

    namespace ModularAPI\HTTP;
    use ModularAPI\Abstracts\AuthenticationType;
    use ModularAPI\Exceptions\InvalidRequestQueryException;
    use ModularAPI\Exceptions\MissingParameterException;
    use ModularAPI\Exceptions\UnsupportedClientException;
    use ModularAPI\Objects\Parameter;
    use ModularAPI\Objects\RequestAuthentication;
    use ModularAPI\Objects\RequestQuery;
    use ModularAPI\Utilities\Checker;

    /**
     * Class Request
     * @package ModularAPI\HTTP
     */
    class Request
    {
        /**
         * Parses the request query
         *
         * @return RequestQuery
         * @throws InvalidRequestQueryException
         * @throws UnsupportedClientException
         */
        public static function parseQuery(): RequestQuery
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            if(isset($_GET['version']) == false)
            {
                throw new InvalidRequestQueryException();
            }
            else
            {
                if(strlen($_GET['version']) == 0)
                {
                    throw new InvalidRequestQueryException();
                }
            }

            if(isset($_GET['module']) == false)
            {
                throw new InvalidRequestQueryException();
            }
            else
            {
                if(strlen($_GET['module']) == 0)
                {
                    throw new InvalidRequestQueryException();
                }
            }

            $RequestQuery = new RequestQuery();
            $RequestQuery->Version = $_GET['version'];
            $RequestQuery->Module = $_GET['module'];
            $RequestQuery->RequestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

            return $RequestQuery;
        }

        /**
         * Parses the Authentication Method used
         *
         * @return RequestAuthentication
         * @throws UnsupportedClientException
         */
        public static function parseAuthentication(): RequestAuthentication
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            $RequestAuthentication = new RequestAuthentication();

            if(isset($_GET['api_key']))
            {
                $RequestAuthentication->Type = AuthenticationType::APIKey;
                $RequestAuthentication->Key = $_GET['api_key'];
                $RequestAuthentication->Certificate = null;
                return $RequestAuthentication;
            }

            if(isset($_POST['api_key']))
            {
                $RequestAuthentication->Type = AuthenticationType::APIKey;
                $RequestAuthentication->Key = $_POST['api_key'];
                $RequestAuthentication->Certificate = null;
                return $RequestAuthentication;
            }

            if(isset($_GET['certificate']))
            {
                if(Checker::isBase64($_GET['certificate']) == false)
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = 'INVALID';
                    $RequestAuthentication->Key = null;

                    return $RequestAuthentication;
                }

                $CertificateData = base64_decode($_GET['certificate'], true);

                if($CertificateData !== false)
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = $CertificateData;
                    $RequestAuthentication->Key = null;
                }
                else
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = 'INVALID';
                    $RequestAuthentication->Key = null;

                    return $RequestAuthentication;
                }

                return $RequestAuthentication;
            }

            if(isset($_POST['certificate']))
            {
                if(Checker::isBase64($_POST['certificate']) == false)
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = 'INVALID';
                    $RequestAuthentication->Key = null;

                    return $RequestAuthentication;
                }

                $CertificateData = base64_decode($_POST['certificate'], true);

                if($CertificateData !== false)
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = $CertificateData;
                    $RequestAuthentication->Key = null;
                }
                else
                {
                    $RequestAuthentication->Type = AuthenticationType::Certificate;
                    $RequestAuthentication->Certificate = 'INVALID';
                    $RequestAuthentication->Key = null;

                    return $RequestAuthentication;
                }

                $RequestAuthentication->Type = AuthenticationType::Certificate;
                $RequestAuthentication->Certificate = base64_decode($_POST['certificate'], true);
                $RequestAuthentication->Key = null;

                return $RequestAuthentication;
            }

            $RequestAuthentication->Type = AuthenticationType::None;
            $RequestAuthentication->Certificate = null;
            $RequestAuthentication->Key = null;

            return $RequestAuthentication;
        }

        /**
         * Retrieves all parameters that are expected
         *
         * @param array $expectedParameter
         * @return array
         * @throws MissingParameterException
         * @throws UnsupportedClientException
         */
        public static function getParameters(array $expectedParameter): array
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            if(count($expectedParameter) == 0)
            {
                return array();
            }

            $requestParameters = array();

            foreach($expectedParameter as $parameter_name => $parameter_properties)
            {
                $ParameterObject = Parameter::fromArray($parameter_name, $parameter_properties);

                $ParameterFound = false;

                if(isset($_GET))
                {
                    foreach($_GET as $getParameter => $getValue)
                    {
                        if(strtoupper($getParameter) == strtoupper($ParameterObject->Name))
                        {
                            $requestParameters[$ParameterObject->Name] = $getValue;
                            $ParameterFound = true;
                            break;
                        }
                    }
                }

                if(isset($_POST))
                {
                    foreach($_POST as $postParameter => $postValue)
                    {
                        if(strtoupper($postParameter) == strtoupper($ParameterObject->Name))
                        {
                            $requestParameters[$ParameterObject->Name] = $postValue;
                            $ParameterFound = true;
                            break;
                        }
                    }
                }

                if($ParameterFound == false)
                {
                    if($ParameterObject->Required == true)
                    {
                        throw new MissingParameterException($ParameterObject->Name);
                    }
                    else
                    {
                        $requestParameters[$ParameterObject->Name] = $ParameterObject->Default;
                    }
                }
            }

            return $requestParameters;
        }
    }