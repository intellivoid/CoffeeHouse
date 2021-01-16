<?php


    namespace CoffeeHouse\Objects\Timex;


    /**
     * Range begin/end/duration
     * (this is not part of the timex standard and is typically null, available if sutime.includeRange is true)
     *
     * Class Range
     * @package CoffeeHouse\Objects\Timex
     */
    class Range
    {
        /**
         * @var string
         */
        public $begin;

        /**
         * @var string
         */
        public $end;

        /**
         * @var string
         */
        public $duration;

        /**
         * Range constructor.
         * @param string $begin
         * @param string $end
         * @param string $duration
         */
        public function __construct(string $begin, string $end, string $duration)
        {
            $this->begin = $begin;
            $this->end = $end;
            $this->duration = $duration;
        }
    }