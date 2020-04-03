<?php

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    $DeepAnalytics->tallyHourly('example', 0, 'clicks');
    $DeepAnalytics->tallyHourly('example', 0, 'requests', 2);
    $DeepAnalytics->tallyHourly('example', 0, 'downloads', 5);