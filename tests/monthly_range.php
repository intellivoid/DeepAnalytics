<?php

    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    var_dump($DeepAnalytics->getMonthlyDataRange('example', 'clicks'));