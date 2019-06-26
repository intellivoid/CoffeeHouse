<?php

    namespace ModularAPI\Objects;

    /**
     * Class Policies
     * @package ModularAPI\Objects
     */
    class Policies
    {
        /**
         * Indicates if authentication is required throughout the whole API
         *
         * @var bool
         */
        public $AuthenticatedRequired;

        /**
         * Indicates if when authentication is required, the user must use a certificate instead of a API Key
         *
         * @var bool
         */
        public $ForceCertificate;

        /**
         * Converts object to array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'AUTHENTICATION_REQUIRED' => $this->AuthenticatedRequired,
                'FORCE_CERTIFICATE' => $this->ForceCertificate
            );
        }

        /**
         * Creates the object from an array
         * 
         * @param array $data
         * @return Policies
         */
        public static function fromArray(array $data): Policies
        {
            $PoliciesObject = new Policies();
        
            if(isset($data['AUTHENTICATION_REQUIRED']))
            {
                $PoliciesObject->AuthenticatedRequired = (bool)$data['AUTHENTICATION_REQUIRED'];
            }
            else
            {
                $PoliciesObject->AuthenticatedRequired = false;
            }
            
            if(isset($data['FORCE_CERTIFICATE']))
            {
                $PoliciesObject->ForceCertificate = (bool)$data['FORCE_CERTIFICATE'];
            }
            else
            {
                $PoliciesObject->ForceCertificate = false;
            }
        
            return $PoliciesObject;
        }
    }