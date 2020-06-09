<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Objects\ServerInterfaceConnection;

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
         * @param string|ServerInterfaceModule $module
         * @param string $path
         * @param array $parameters
         * @return string
         */
        public function sendRequest(string $module, string $path, array $parameters): string
        {
            $InterfaceConnection = $this->resolveInterfaceConnection($module);

            $CurlClient = curl_init();
            curl_setopt($CurlClient, CURLOPT_URL, $InterfaceConnection->generateAddress(false) . $path);
            curl_setopt($CurlClient, CURLOPT_POST, 1);
            curl_setopt($CurlClient, CURLOPT_POSTFIELDS, http_build_query($parameters));
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
            $ServerInterfaceConnection->Host = $this->coffeehouse->getServerConfiguration()['Host'];
            $ServerInterfaceConnection->Module = $module;

            switch($module)
            {
                case ServerInterfaceModule::SpamDetection:
                    $ServerInterfaceConnection->Port = $this->coffeehouse->getServerConfiguration()['SpamDetectionPort'];
                    break;

                default:
                    throw new InvalidServerInterfaceModuleException();
            }

            return $ServerInterfaceConnection;
        }
    }