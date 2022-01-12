<?php

    use DeepAnalytics\DeepAnalytics;

    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    $DeepAnalytics = new DeepAnalytics();

    $DeepAnalytics->tallyMonthly('example', 'clicks', null);
    $DeepAnalytics->tallyMonthly('example', 'requests', null, 2);
    $DeepAnalytics->tallyMonthly('example', 'downloads', null, 7);