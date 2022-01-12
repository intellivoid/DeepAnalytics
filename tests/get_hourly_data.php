<?php

    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    foreach($DeepAnalytics->getHourlyDataRange('example', 'clicks') as $item)
    {
        var_dump($DeepAnalytics->getHourlyDataById('example', $item['id']));
    }