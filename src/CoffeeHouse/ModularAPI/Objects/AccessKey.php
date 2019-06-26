<?php

    namespace ModularAPI\Objects;
    use ModularAPI\Abstracts\AccessKeyStatus;
    use ModularAPI\Objects\AccessKey\Analytics;
    use ModularAPI\Objects\AccessKey\Permissions;
    use ModularAPI\Objects\AccessKey\Signatures;
    use ModularAPI\Objects\AccessKey\Usage;

    /**
     * Class AccessKey
     * @package ModularAPI\Objects
     */
    class AccessKey
    {
        /**
         * Database ID of the access key
         *
         * @var int
         */
        public $ID;

        /**
         * The Public ID of the access key
         *
         * @var string
         */
        public $PublicID;

        /**
         * The API Key
         *
         * @var string
         */
        public $PublicKey;

        /**
        public $Signature;

        /**
         * The status of this access key
         *
         * @var AccessKeyStatus
         */
        public $State;

        /**
         * The usage and validation for this access key
         *
         * @var Usage
         */
        public $Usage;

        /**
         * Module permission access data
         *
         * @var Permissions
         */
        public $Permissions;

        /**
         * The analytics data for this access key
         *
         * @var Analytics
         */
        public $Analytics;

        /**
         * Encryption signature information
         *
         * @var Signatures
         */
        public $Signatures;

        /**
         * The Unix  Timestamp that this Access Key was created
         *
         * @var int
         */
        public $CreationDate;

        /**
         * Creates an array from the object
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'public_id' => $this->PublicID,
                'public_key' => $this->PublicKey,
                'state' => $this->State,
                'usage' => $this->Usage->toArray(),
                'permissions' => $this->Permissions->toArray(),
                'analytics' => $this->Analytics->toArray(),
                'signatures' => $this->Signatures->toArray(),
                'creation_date' => $this->CreationDate
            );
        }

        /**
         * Creates an object from array
         *
         * @param array $data
         * @return AccessKey
         */
        public static function fromArray(array $data): AccessKey
        {
            $AccessKeyObject = new AccessKey();

            if(isset($data['id']))
            {
                $AccessKeyObject->ID = (int)$data['id'];
            }

            if(isset($data['public_id']))
            {
                $AccessKeyObject->PublicID = (string)$data['public_id'];
            }

            if(isset($data['public_key']))
            {
                $AccessKeyObject->PublicKey = (string)$data['public_key'];
            }

            if(isset($data['state']))
            {
                $AccessKeyObject->State = (int)$data['state'];
            }

            if(isset($data['usage']))
            {
                $AccessKeyObject->Usage = Usage::fromArray($data['usage']);
            }

            if(isset($data['permissions']))
            {
                $AccessKeyObject->Permissions = Permissions::fromArray($data['permissions']);
            }

            if(isset($data['analytics']))
            {
                $AccessKeyObject->Analytics = Analytics::fromArray($data['analytics']);
            }

            if(isset($data['signatures']))
            {
                $AccessKeyObject->Signatures = Signatures::fromArray($data['signatures']);
            }

            if(isset($data['creation_date']))
            {
                $AccessKeyObject->CreationDate = (int)$data['creation_date'];
            }

            return $AccessKeyObject;
        }
    }