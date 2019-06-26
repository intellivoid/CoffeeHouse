<?php

    namespace ModularAPI\Objects;

    /**
     * Class API
     * @package ModularAPI\Objects\
     */
    class API
    {
        /**
         * Indicates if the API is Available or not
         *
         * @var bool
         */
        public $Available;

        /**
         * The message displayed if the API is Unavailable
         *
         * @var string
         */
        public $UnavailableMessage;

        /**
         * Converts Object to Array
         * 
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'AVAILABLE' => $this->Available,
                'UNAVAILABLE_MESSAGE' => $this->UnavailableMessage
            );
        }

        /**
         * Creates Object from Array
         *
         * @param array $data
         * @return API
         */
        public static function fromArray(array $data): API
        {
            $APIObject = new API();

            if(isset($data['AVAILABLE']))
            {
                $APIObject->Available = (bool)$data['AVAILABLE'];
            }
            else
            {
                $APIObject->Available = true;
            }

            if(isset($data['UNAVAILABLE_MESSAGE']))
            {
                $APIObject->UnavailableMessage = (string)$data['UNAVAILABLE_MESSAGE'];
            }
            else
            {
                $APIObject->UnavailableMessage = 'This API Version is unavailable, please contact the administrator';
            }

            return $APIObject;
        }

    }