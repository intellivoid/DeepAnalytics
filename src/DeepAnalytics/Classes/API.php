<?php

    namespace DeepAnalytics\Classes;

    use DeepAnalytics\Abstracts\ApiActions;
    use DeepAnalytics\DeepAnalytics;
    use DeepAnalytics\Exceptions\DataNotFoundException;
    use DeepAnalytics\Exceptions\MissingParameterException;
    use DeepAnalytics\Objects\Date;
    use DeepAnalytics\Utilities;
    use Exception;

    class API
    {
        /**
         * @var string
         */
        private static $ApiVersion = '1.0';

        /**
         * @var string[]
         */
        public static $Locale = [
            'DEEPANALYTICS_NO_DATA_ERROR' => 'No Data Available',
            'DEEPANALYTICS_GENERIC_ERROR' => 'DeepAnalytics Error (%s)',
            'DEEPANALYTICS_MONTHLY_USAGE' => 'Monthly Usage',
            'DEEPANALYTICS_DAILY_USAGE' => 'Daily Usage',
            'DEEPANALYTICS_DATA_SELECTOR' => 'Data',
            'DEEPANALYTICS_DATE_SELECTOR' => 'Date',
            'DEEPANALYTICS_DATA_ALL' => 'All'
        ];

        /**
         * @var string[]
         */
        private static $NamesLocale = [];

        /**
         * Determines the requested DeepAnalytics API action to execute
         *
         * @return string|ApiActions
         */
        public static function getAction(): string
        {
            if(isset($_GET['deepanalytics_action']) && isset($_GET['invoke_da']) && $_GET['invoke_da'] == 1)
            {
                switch(strtolower($_GET['deepanalytics_action']))
                {
                    case 'get_range':
                        return ApiActions::GetRange;

                    case 'get_locale':
                        return ApiActions::GetLocale;

                    case 'get_monthly_data':
                        return ApiActions::GetMonthlyData;

                    case 'get_hourly_date':
                        return ApiActions::GetHourlyData;

                    default:
                        return ApiActions::None;
                }
            }

            return ApiActions::None;
        }

        /**
         * @return string[]
         */
        public static function getLocale(): array
        {
            return self::$Locale;
        }

        /**
         * @param string[] $Locale
         */
        public static function setLocale(array $Locale): void
        {
            self::$Locale = $Locale;
        }

        /**
         * @return string[]
         */
        public static function getNamesLocale(): array
        {
            return self::$NamesLocale;
        }

        /**
         * @param string[] $NamesLocale
         */
        public static function setNamesLocale(array $NamesLocale): void
        {
            self::$NamesLocale = $NamesLocale;
        }

        /**
         * Returns the response for a GetLocale API action
         *
         * @return string
         */
        public static function getLocaleResponse(): string
        {
            return json_encode([
                'api_version' => self::$ApiVersion,
                'success' => true,
                'payload' => self::$Locale
            ], JSON_UNESCAPED_SLASHES);
        }

        /**
         * Returns hourly data about the requested data
         *
         * @param DeepAnalytics $deepAnalytics
         * @param string $collection
         * @param array $names
         * @param string|null $reference_id
         * @return string
         * @throws MissingParameterException
         */
        public static function getHourlyData(DeepAnalytics $deepAnalytics, string $collection, array $names, ?string $reference_id=null): string
        {
            if(isset($_POST['year']) == false)
                throw new MissingParameterException('year', 10);

            if(isset($_POST['month']) == false)
                throw new MissingParameterException('month', 11);

            if(isset($_POST['day']) == false)
                throw new MissingParameterException('day', 12);

            $SelectedDate = new Date();
            $SelectedDate->Year = (int)$_POST['year'];
            $SelectedDate->Month = (int)$_POST['month'];
            $SelectedDate->Day = (int)$_POST['day'];

            $Results = array();

            foreach($names as $name)
            {
                try
                {
                    $hourlyDataResults = $deepAnalytics->getHourlyData(
                        $collection, $name, $reference_id, true,
                        (int)$_POST["year"], (int)$_POST["month"], (int)$_POST["day"]);

                    $return_results = [
                        "name" => $hourlyDataResults->Name,
                        "total" => $hourlyDataResults->Total,
                        "data" =>[]
                    ];

                    foreach($hourlyDataResults->getData(true) as $key => $value)
                    {
                        $return_results["data"][Utilities::generateFullHourStamp($SelectedDate, $key)] = $value;
                    }

                    $Results[$name] = $return_results;
                }
                catch(DataNotFoundException $e)
                {
                    unset($e);
                }
            }

            return json_encode([
                'api_version' => self::$ApiVersion,
                'success' => true,
                'payload' => $Results
            ], JSON_UNESCAPED_SLASHES);
        }

        /**
         * Returns monthly analytical data about the requested data
         *
         * @param DeepAnalytics $deepAnalytics
         * @param string $collection
         * @param array $names
         * @param string|null $reference_id
         * @return string
         * @throws MissingParameterException
         */
        public static function getMonthlyData(DeepAnalytics $deepAnalytics, string $collection, array $names, ?string $reference_id=null): string
        {
            if(isset($_POST['year']) == false)
                throw new MissingParameterException('year', 10);

            if(isset($_POST['month']) == false)
                throw new MissingParameterException('month', 11);

            $Results = array();

            foreach($names as $name)
            {
                try
                {
                    $monthlyData = $deepAnalytics->getMonthlyData(
                        $collection, $name, $reference_id, true,
                        (int)$_POST["year"], (int)$_POST["month"]);

                    $return_results = [
                        "total" => $monthlyData->Total,
                        "data" => []
                    ];

                    foreach($monthlyData->getData(true) as $key => $value)
                    {
                        $return_results['data'][Utilities::generateHourlyStamp(
                            (int)$_POST['year'], (int)$_POST['month'], $key
                        )] = $value;
                    }

                    $Results[$name] = $return_results;
                }
                catch(DataNotFoundException $e)
                {
                    unset($e);
                }
            }

            return json_encode([
                'api_version' => self::$ApiVersion,
                'success' => true,
                'payload' => $Results
            ], JSON_UNESCAPED_SLASHES);
        }

        /**
         * Returns the range of the data that's available
         *
         * @param DeepAnalytics $deepAnalytics
         * @param string $collection
         * @param array $names
         * @param string|null $reference_id
         * @return string
         */
        public static function getRange(DeepAnalytics $deepAnalytics, string $collection, array $names, ?string $reference_id=null): string
        {
            $results = [];

            foreach($names as $name)
            {
                $results[$name] = [
                    'monthly' => $deepAnalytics->getMonthlyDataRange($collection, $name, $reference_id),
                    'hourly' => $deepAnalytics->getHourlyDataRange($collection, $name, $reference_id),
                    'text' => (self::$NamesLocale[$name] ?? $name)
                ];
            }

            return json_encode([
                'api_version' => self::$ApiVersion,
                'success' => true,
                'payload' => $results
            ], JSON_UNESCAPED_SLASHES);
        }

        /**
         * Detects the API action request and returns the correct response
         *
         * @param DeepAnalytics $deepAnalytics
         * @param string $collection
         * @param array $names
         * @param string|null $reference_id
         * @return string|null
         */
        public static function handle(DeepAnalytics $deepAnalytics, string $collection, array $names, ?string $reference_id=null): ?string
        {
            try
            {
                switch(self::getAction())
                {
                    case ApiActions::GetRange:
                        return self::getRange($deepAnalytics, $collection, $names, $reference_id);

                    case ApiActions::GetLocale:
                        return self::getLocaleResponse();

                    case ApiActions::GetHourlyData:
                        return self::getHourlyData($deepAnalytics, $collection, $names, $reference_id);

                    case ApiActions::GetMonthlyData:
                        return self::getMonthlyData($deepAnalytics, $collection, $names, $reference_id);
                }
            }
            catch(MissingParameterException $e)
            {
                return json_encode([
                    'api_version' => self::$ApiVersion,
                    'success' => false,
                    'error_code' => $e->getCode(),
                    'error_message' => $e->getMessage()
                ], JSON_UNESCAPED_SLASHES);
            }
            catch(Exception $e)
            {
                return json_encode([
                    'api_version' => self::$ApiVersion,
                    'success' => false,
                    'error_code' => $e->getCode(),
                    'error_message' => 'Internal Server Error'
                ], JSON_UNESCAPED_SLASHES);
            }

            return null;
        }

    }