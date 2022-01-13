<?php

    namespace DeepAnalytics\Abstracts;

    abstract class ApiActions
    {
        const None = 'NONE';

        const GetLocale = 'GET_LOCALE';

        const GetRange = 'GET_RANGE';

        const GetMonthlyData = 'GET_MONTHLY_DATA';

        const GetHourlyData = 'GET_HOURLY_DATA';
    }