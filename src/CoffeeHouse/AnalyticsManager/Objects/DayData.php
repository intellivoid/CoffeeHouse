<?php

    namespace AnalyticsManager\Objects;

    use AnalyticsManager\Exceptions\InvalidDayException;
    use AnalyticsManager\Exceptions\ObjectNotAvailableException;

    class DayData
    {
        /**
         * Indicates if this object is available or not
         *
         * @var bool
         */
        public $available;

        /**
         * The day that this object is associated with
         *
         * @var int
         */
        public $day_date;

        /**
         * The usage data for this object
         *
         * @var array
         */
        public $data;

        /**
         * DayData constructor.
         */
        public function __construct()
        {
            $this->available = false;
            $this->day_date = null;
            $this->data = array();
        }

        /**
         * Returns the object as an array
         *
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'available' => $this->available,
                'day_date' => $this->day_date,
                'data' => $this->data
            );
        }

        /**
         * Creates object from array data
         *
         * @param array $data
         * @return DayData
         */
        public static function fromArray(array $data): DayData
        {
            $DayDataObject = new DayData();

            if(isset($data['available']))
            {
                $DayDataObject->available = (bool)$data['available'];
            }

            if(isset($data['day_date']))
            {
                $DayDataObject->day_date = (int)$data['day_date'];
            }

            if(isset($data['data']))
            {
                $DayDataObject->data = $data['data'];
            }

            return $DayDataObject;
        }

        /**
         * Tallies the usage for today
         *
         * @param int $amount
         */
        public function tally(int $amount = 1)
        {
            $this->data[(int)date('G')] += $amount;
        }

        /**
         * Sets a hard value
         *
         * @param int $hour
         * @param int $amount
         * @throws InvalidDayException
         * @throws ObjectNotAvailableException
         */
        public function set(int $hour, int $amount)
        {
            if($this->available == false)
            {
                throw new ObjectNotAvailableException();
            }

            if(isset($this->data[$hour]) == false)
            {
                throw new InvalidDayException();
            }

            $this->data[$hour] = $amount;
        }

        /**
         * Gets the total value
         *
         * @return int
         * @throws ObjectNotAvailableException
         */
        public function total(): int
        {
            if($this->available == false)
            {
                throw new ObjectNotAvailableException();
            }

            $total = 0;

            foreach($this->data as $hour => $usage)
            {
                $total += $usage;
            }

            return $total;
        }
    }