<?php

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    foreach($DeepAnalytics->getHourlyDataRange('example', 'clicks') as $item)
    {
        var_dump($DeepAnalytics->getHourlyDataById('example', $item['id']));
    }