<?php

    namespace AnalyticsManager\Objects;
    use AnalyticsManager\Utilities\Builder;

    /**
     * Class Record
     * @package AnalyticsManager\Objects
     */
    class Record
    {
        /**
         * The ID of this record
         *
         * @var int
         */
        public $ID;

        /**
         * The Public ID of this record
         *
         * @var string
         */
        public $PublicID;

        /**
         * Custom name to reference this record
         *
         * @var string
         */
        public $Name;

        /**
         * The data for this month
         *
         * @var MonthData
         */
        public $ThisMonth;

        /**
         * The data for last month
         *
         * @var MonthData
         */
        public $LastMonth;

        /**
         * The data for today
         *
         * @var DayData
         */
        public $Today;

        /**
         * The data for yesterday
         *
         * @var DayData
         */
        public $Yesterday;

        /**
         * The Unix Timestamp of when this record was created
         *
         * @var int
         */
        public $CreationTimestamp;

        /**
         * The Unix Timestamp of when this record was last updated
         *
         * @var int
         */
        public $LastUpdated;

        /**
         * Record constructor.
         */
        public function __construct()
        {
            $this->ID = 0;
            $this->PublicID = null;
            $this->Name = null;
            $this->ThisMonth = new MonthData();
            $this->LastMonth = new MonthData();
            $this->Today = new DayData();
            $this->Yesterday = new DayData();
            $this->CreationTimestamp = 0;
            $this->LastUpdated = 0;
        }

        /**
         * Returns object as array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'public_id' => $this->PublicID,
                'name' => $this->Name,
                'this_month' => $this->ThisMonth->toArray(),
                'last_month' => $this->LastMonth->toArray(),
                'today' => $this->Today->toArray(),
                'yesterday' => $this->Yesterday->toArray(),
                'creation_timestamp' => $this->CreationTimestamp,
                'last_updated' => $this->LastUpdated
            );
        }

        /**
         * Creates object from array
         *
         * @param array $data
         * @return Record
         */
        public static function fromArray(array $data): Record
        {
            $RecordObject = new Record();

            if(isset($data['id']))
            {
                $RecordObject->ID = (int)$data['id'];
            }

            if(isset($data['public_id']))
            {
                $RecordObject->PublicID = $data['public_id'];
            }

            if(isset($data['name']))
            {
                $RecordObject->Name = $data['name'];
            }

            if(isset($data['this_month']))
            {
                $RecordObject->ThisMonth = MonthData::fromArray($data['this_month']);
            }

            if(isset($data['last_month']))
            {
                $RecordObject->LastUpdated = MonthData::fromArray($data['last_month']);
            }

            if(isset($data['today']))
            {
                $RecordObject->Today = DayData::fromArray($data['today']);
            }

            if(isset($data['yesterday']))
            {
                $RecordObject->Yesterday = DayData::fromArray($data['yesterday']);
            }

            if(isset($data['creation_timestamp']))
            {
                $RecordObject->CreationTimestamp = (int)$data['creation_timestamp'];
            }

            if(isset($data['last_updated']))
            {
                $RecordObject->LastUpdated = (int)$data['last_updated'];
            }

            return $RecordObject;
        }

        /**
         * Syncs object to be up to date
         */
        public function sync()
        {
            if($this->ThisMonth->month_date !== (int)date('n'))
            {
                $this->LastMonth = $this->ThisMonth;
                $this->ThisMonth = new MonthData();
                $this->ThisMonth->available = true;
                $this->ThisMonth->data = Builder::buildMonth((int)date('n'), (int)date('Y'));
                $this->ThisMonth->month_date = (int)date('n');
            }

            if($this->Today->day_date !== (int)date('j'))
            {
                $this->Yesterday = $this->Today;
                $this->Today = new DayData();
                $this->Today->available = true;
                $this->Today->day_date = (int)date('j');
                $this->Today->data = Builder::buildDay();
            }
        }

        /**
         * Tallies data
         *
         * @param int $amount
         * @param bool $month
         * @param bool $day
         */
        public function tally(int $amount, bool $month = true, bool $day = true)
        {
            if($month == true)
            {
                $this->ThisMonth->tally($amount);
            }

            if($day == true)
            {
                $this->Today->tally($amount);
            }
        }

    }