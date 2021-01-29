<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Objects\ServerInterfaceConnection;
    use Exception;

    /**
     * Class ServerInterface
     * @package CoffeeHouse\Classes
     */
    class ServerInterface
    {
        /**
         * @var CoffeeHouse
         */
        private $coffeehouse;

        /**
         * ServerInterface constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeehouse = $coffeeHouse;
        }

        /**
         * @param string $module
         * @param string $path
         * @param array $parameters
         * @param bool $ping
         * @return string
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws InvalidServerInterfaceModuleException
         * @throws ServerInterfaceException
         * @noinspection DuplicatedCode
         */
        public function sendRequest(string $module, string $path, array $parameters, bool $ping=true): string
        {
            if($ping)
            {
                try
                {
                    $this->sendRequest(ServerInterfaceModule::PingService, "/", [], false);
                }
                catch(ServerInterfaceException)
                {
                    throw new CoffeeHouseUtilsNotReadyException("CoffeeHouse-Utils is not running or is not yet ready.");
                }
            }

            $InterfaceConnection = $this->resolveInterfaceConnection($module);

            $CurlClient = curl_init();
            curl_setopt($CurlClient, CURLOPT_URL, $InterfaceConnection->generateAddress(false) . $path);
            curl_setopt($CurlClient, CURLOPT_POST, 1);
            curl_setopt($CurlClient, CURLOPT_POSTFIELDS, http_build_query($parameters));
            curl_setopt($CurlClient, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($CurlClient, CURLOPT_FAILONERROR, true);

            $response = curl_exec($CurlClient);

            if (curl_errno($CurlClient))
            {
                $error_response = curl_error($CurlClient);
                curl_close($CurlClient);

                throw new ServerInterfaceException(
                    $error_response, $InterfaceConnection->generateAddress(false) . $path, $parameters);
            }

            curl_close($CurlClient);
            return $response;
        }

        /**
         * Same as sendRequest but can include data in the request body
         *
         * @param string $module
         * @param string $path
         * @param string $data
         * @param array $parameters
         * @param bool $ping
         * @return string
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws InvalidServerInterfaceModuleException
         * @throws ServerInterfaceException
         * @noinspection DuplicatedCode
         */
        public function sendDataRequest(string $module, string $path, string $data, array $parameters, bool $ping=true): string
        {
            if($ping)
            {
                try
                {
                    $this->sendRequest(ServerInterfaceModule::PingService, "/", [], false);
                }
                catch(ServerInterfaceException)
                {
                    throw new CoffeeHouseUtilsNotReadyException("CoffeeHouse-Utils is not running or is not yet ready.");
                }
            }

            $InterfaceConnection = $this->resolveInterfaceConnection($module);

            $CurlClient = curl_init();
            curl_setopt($CurlClient, CURLOPT_URL, $InterfaceConnection->generateAddress(false) . $path . "?" . http_build_query($parameters));
            curl_setopt($CurlClient, CURLOPT_POST, 1);
            curl_setopt($CurlClient, CURLOPT_POSTFIELDS, urlencode($data));
            curl_setopt($CurlClient, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($CurlClient, CURLOPT_FAILONERROR, true);
            curl_setopt($CurlClient, CURLOPT_HTTPHEADER, [
                "Content-Type: application/x-www-form-urlencoded"
            ]);

            $response = curl_exec($CurlClient);

            if (curl_errno($CurlClient))
            {
                $error_response = curl_error($CurlClient);
                curl_close($CurlClient);

                throw new ServerInterfaceException(
                    $error_response, $InterfaceConnection->generateAddress(false) . $path, $parameters);
            }

            curl_close($CurlClient);
            return $response;
        }

        /**
         * Resolves the interface connection
         *
         * @param string $module
         * @return ServerInterfaceConnection
         * @throws InvalidServerInterfaceModuleException
         */
        public function resolveInterfaceConnection(string $module): ServerInterfaceConnection
        {
            $ServerInterfaceConnection = new ServerInterfaceConnection();
            $ServerInterfaceConnection->Host = $this->coffeehouse->getUtilsConfiguration()["Host"];
            $ServerInterfaceConnection->Module = $module;

            switch($module)
            {
                case ServerInterfaceModule::PingService:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["PingPort"];
                    break;

                case ServerInterfaceModule::SpamPrediction:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["SpamPredictionPort"];
                    break;

                case ServerInterfaceModule::NsfwPrediction:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["NsfwPredictionPort"];
                    break;

                case ServerInterfaceModule::TranslateService:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["TranslatePort"];
                    break;

                case ServerInterfaceModule::CoreNLP:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["CoreNlpPort"];
                    break;

                case ServerInterfaceModule::EmotionPrediction:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["EmotionsPort"];
                    break;

                case ServerInterfaceModule::LanguagePrediction:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getUtilsConfiguration()["LanguageDetectionPort"];
                    break;

                default:
                    throw new InvalidServerInterfaceModuleException();
            }

            return $ServerInterfaceConnection;
        }
    }