<?php

    namespace ModularAPI\Objects;

    /**
     * Class Parameter
     * @package ModularAPI\Objects
     */
    class Parameter
    {
        /**
         * The name of the Parameter
         *
         * @var string
         */
        public $Name;

        /**
         * Indicates if this Parameter is required
         *
         * @var bool
         */
        public $Required;

        /**
         * The default value of this parameter (If this parameter is not required)
         *
         * @var string
         */
        public $Default;

        /**
         * Converts object to array
         * 
         * @return array
         */
        public function toArray(): array
        {
            return array(
                strtoupper($this->Name) => array(
                    'REQUIRED' => $this->Required,
                    'DEFAULT' => $this->Default
                )
            );
        }

        /**
         * Creates object from array
         * 
         * @param string $name
         * @param array $data
         * @return Parameter
         */
        public static function fromArray(string $name, array $data): Parameter
        {
            $parameter = new Parameter();

            $parameter->Name = $name;

            if(isset($data['REQUIRED']))
            {
                $parameter->Required = (bool)$data['REQUIRED'];
            }
            else
            {
                $parameter->Required = true;
            }
            
            if(isset($data['DEFAULT']))
            {
                $parameter->Default = (string)$data['DEFAULT'];
            }
            else
            {
                $parameter->Default = null;
            }
            
            return $parameter;
        }
    }