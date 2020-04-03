<?php


    namespace DeepAnalytics\Objects;

    /**
     * Class Month
     * @package DeepAnalytics\Objects
     */
    class Month
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
         * Unique stamp for this record
         *
         * @var string
         */
        public $Stamp;

        /**
         * The days of the month with their representative tallies
         *
         * @var array
         */
        public $Data;

        /**
         * Returns an array which represents this structure
         *
         * @return array
         */
        function toArray(): array
        {
            return array(
                'id' => $this->ID,
                'reference_id' => (int)$this->ReferenceID,
                'name' => $this->Name,
                'year' => (int)$this->Year,
                'month' => (int)$this->Month,
                'stamp' => $this->Stamp,
                'data' => $this->Data
            );
        }
    }