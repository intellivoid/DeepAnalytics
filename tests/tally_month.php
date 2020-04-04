<?php

    use DeepAnalytics\DeepAnalytics;

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new DeepAnalytics();

    $DeepAnalytics->tallyMonthly('example', 'clicks', 2);
    $DeepAnalytics->tallyMonthly('example', 'requests', 2, 2);
    $DeepAnalytics->tallyMonthly('example', 'downloads', 2, 7);