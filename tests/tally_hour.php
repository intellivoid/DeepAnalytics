<?php

    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    $DeepAnalytics->tallyHourly('example',  'clicks');
    $DeepAnalytics->tallyHourly('example', 'requests', null, 2);
    $DeepAnalytics->tallyHourly('example', 'downloads', null, 5);