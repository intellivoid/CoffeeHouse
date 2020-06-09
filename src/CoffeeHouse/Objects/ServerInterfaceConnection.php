<?php


    namespace CoffeeHouse\Objects;


    use CoffeeHouse\Abstracts\ServerInterfaceModule;

    /**
     * Class ServerInterfaceConnection
     * @package CoffeeHouse\Objects
     */
    class ServerInterfaceConnection
    {
        /**
         * The host of the server interface module
         *
         * @var string
         */
        public $Host;

        /**
         * The port of the server interface port
         *
         * @var int
         */
        public $Port;

        /**
         * @var string|ServerInterfaceModule
         */
        public $Module;

        /**
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'host' => $this->Host,
                'port' => (int)$this->Port,
                'module' => $this->Module
            );
        }

        /**
         * @param array $data
         * @return ServerInterfaceConnection
         */
        public static function fromArray(array $data): ServerInterfaceConnection
        {
            $ServerInterfaceConnectionObject = new ServerInterfaceConnection();

            if(isset($data['host']))
            {
                $ServerInterfaceConnectionObject->Host = $data['host'];
            }

            if(isset($data['port']))
            {
                $ServerInterfaceConnectionObject->Port = (int)$data['port'];
            }

            if(isset($data['module']))
            {
                $ServerInterfaceConnectionObject->Module = $data['module'];
            }

            return $ServerInterfaceConnectionObject;
        }
    }