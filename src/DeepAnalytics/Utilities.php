<?php


    namespace DeepAnalytics;

    use DeepAnalytics\Objects\HourlyData;
    use MongoDB\Model\BSONDocument;

    /**
     * Class Utilities
     * @package DeepAnalytics
     */
    class Utilities
    {
        /**
         * Generates an hourly stamp
         *
         * @param int $year
         * @param int $month
         * @param $day
         * @return string
         */
        static function generateHourlyStamp(int $year, int $month, int $day): string
        {
            return "$year-$month-$day";
        }

        /**
         * Generates a month stamp
         *
         * @param int $year
         * @param int $month
         * @return string
         */
        static function generateMonthStamp(int $year, int $month): string
        {
            return "$year-$month";
        }

        /**
         * Generates an array of a 24 hour timeline
         *
         * @return array
         */
        static function generateHourArray(): array
        {
            $current_count = 0;
            $results = array();

            while(true)
            {
                if($current_count > 24)
                {
                    break;
                }

                $results[$current_count] = 0;
                $current_count += 1;
            }

            return $results;
        }

        /**
         * Constructs HourlyData object from BSONDocument
         *
         * @param BSONDocument|array|object $document
         * @return HourlyData
         */
        static function BSONDocumentToHourlyData($document): HourlyData
        {
            $DocumentData = (array)$document->jsonSerialize();
            $DocumentData['_id'] = (string)$DocumentData['_id'];
            $DocumentData['date'] = (array)$DocumentData['date']->jsonSerialize();
            $DocumentData['data'] = (array)$DocumentData['data']->jsonSerialize();

            return HourlyData::fromArray($DocumentData);
        }
    }