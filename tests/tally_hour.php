<?php

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    $DeepAnalytics->tallyHourly('example',  'clicks');
    $DeepAnalytics->tallyHourly('example', 'requests', null, 2);
    $DeepAnalytics->tallyHourly('example', 'downloads', null, 5);