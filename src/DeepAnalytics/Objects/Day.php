<?php


    namespace DeepAnalytics\Objects;

    /**
     * Class Day
     * @package DeepAnalytics\Objects
     */
    class Day
    {
        /**
         * @var string
         *
         * Unique internal database ID for this record
         */
        public $ID;

        /**
         * @var string
         */
        public $ReferenceID;

        /**
         * The name of this analytical counter
         *
         * @var string
         */
        public $Name;

        /**
         * The year that this record is based in
         *
         * @var int
         */
        public $Year;

        /**
         * The month that this record is based in
         *
         * @var int
         */
        public $Month;

        /**
         * The month that this record is based in
         *
         * @var int
         */
        public $Day;

        /**
         * Unique stamp for this record
         *
         * @var string
         */
        public $Stamp;

        /**
         * Unique month stamp for this record
         *
         * @var string
         */
        public $MonthStamp;

        /**
         * The 24 hour
         *
         * @var array
         */
        public $Data;

        /**
         * @return array
         */
        public function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'reference_id' => (int)$this->ReferenceID,
                'name' => $this->Name,
                'year' => (int)$this->Year,
                'month' => (int)$this->Month,
                'day' => (int)$this->Day,
                'stamp' => $this->Stamp,
                'month_stamp' => $this->MonthStamp,
                'data' => $this->Data
            );
        }
    }