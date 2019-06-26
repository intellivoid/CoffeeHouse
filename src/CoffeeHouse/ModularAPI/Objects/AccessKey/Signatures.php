<?php

    namespace ModularAPI\Objects\AccessKey;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class Signatures
     * @package ModularAPI\Objects\AccessKey
     */
    class Signatures
    {
        /**
         * The signature of the time that signature was created
         *
         * @var string
         */
        public $TimeSignature;

        /**
         * The name of the issuer
         *
         * @var string
         */
        public $IssuerName;

        /**
         * Public Key Signature
         *
         * @var string
         */
        public $PublicSignature;

        /**
         * Private Key Signature
         *
         * @var string
         */
        public $PrivateSignature;

        /**
         * Creates a access certificate that can be used to
         * utilize this key rather than a API Key
         *
         * @return string
         */
        public function createCertificate(): string
        {
            return Hashing::buildCertificateKey($this->IssuerName, $this->PrivateSignature, $this->PublicSignature);
        }

        /**
         * Converts the object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'time_signature' => $this->TimeSignature,
                'issuer_name' => $this->IssuerName,
                'public_signature' => $this->PublicSignature,
                'private_signature' => $this->PrivateSignature
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Signatures
         */
        public static function fromArray(array $data): Signatures
        {
            $SignaturesObject = new Signatures();

            if(isset($data['time_signature']))
            {
                $SignaturesObject->TimeSignature = $data['time_signature'];
            }

            if(isset($data['issuer_name']))
            {
                $SignaturesObject->IssuerName = $data['issuer_name'];
            }

            if(isset($data['public_signature']))
            {
                $SignaturesObject->PublicSignature = $data['public_signature'];
            }

            if(isset($data['private_signature']))
            {
                $SignaturesObject->PrivateSignature = $data['private_signature'];
            }

            return $SignaturesObject;
        }
    }