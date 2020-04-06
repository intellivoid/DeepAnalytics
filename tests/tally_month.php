<?php

    use DeepAnalytics\DeepAnalytics;

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new DeepAnalytics();

    $DeepAnalytics->tallyMonthly('example', 'clicks', null);
    $DeepAnalytics->tallyMonthly('example', 'requests', null, 2);
    $DeepAnalytics->tallyMonthly('example', 'downloads', null, 7);