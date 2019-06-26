<?php

    namespace ModularAPI\Objects\AccessKey;
    use ModularAPI\Utilities\Builder;
    use ModularAPI\Utilities\Hashing;

    /**
     * Class Analytics
     * @package ModularAPI\Objects\AccessKey
     */
    class Analytics
    {
        /**
         * The ID of the last month
         *
         * @var string
         */
        public $LastMonthID;

        /**
         * The usage from the last month
         *
         * @var array
         */
        public $LastMonthUsage;

        /**
         * Indicates if Last Month Data is available or not
         *
         * @var bool
         */
        public $LastMonthAvailable;

        /**
         * The ID of this month
         *
         * @var string
         */
        public $CurrentMonthID;

        /**
         * The usage for this month
         *
         * @var array
         */
        public $CurrentMonthUsage;

        /**
         * Indicates if the current month data is available or not
         *
         * @var bool
         */
        public $CurrentMonthAvailable;

        /**
         * Tracks Usage
         */
        public function trackUsage()
        {
            // Determine if the month ID updated
            if($this->CurrentMonthID !== Hashing::calculateMonthID((int)date('n'), (int)date('Y')))
            {
                $this->LastMonthAvailable = true;
                $this->LastMonthID = $this->CurrentMonthID;
                $this->LastMonthUsage = $this->CurrentMonthUsage;
                $this->CurrentMonthAvailable = true;
                $this->CurrentMonthID = Hashing::calculateMonthID((int)date('n'), (int)date('Y'));
                $this->CurrentMonthUsage = Builder::createMonthArray();
            }

            $this->CurrentMonthUsage[(int)date('j') - 1] += 1;
        }

        /**
         * Converts the object to an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'last_month' => array(
                    'id' => $this->LastMonthID,
                    'available' => $this->LastMonthAvailable,
                    'usage' => $this->LastMonthUsage
                ),
                'current_month' => array(
                    'id' => $this->CurrentMonthID,
                    'available' => $this->CurrentMonthAvailable,
                    'usage' => $this->CurrentMonthUsage
                )
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Analytics
         */
        public static function fromArray(array $data): Analytics
        {
            $AnalyticsObject = new Analytics();

            if(isset($data['last_month']))
            {
                $AnalyticsObject->LastMonthID = $data['last_month']['id'];
                $AnalyticsObject->LastMonthAvailable = $data['last_month']['available'];
                $AnalyticsObject->LastMonthUsage = $data['last_month']['usage'];
            }

            if(isset($data['current_month']))
            {
                $AnalyticsObject->CurrentMonthID = $data['current_month']['id'];
                $AnalyticsObject->CurrentMonthAvailable = $data['current_month']['available'];
                $AnalyticsObject->CurrentMonthUsage = $data['current_month']['usage'];
            }

            return $AnalyticsObject;
        }
    }