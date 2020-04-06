<?php

    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    var_dump($DeepAnalytics->getMonthlyDataRange('example', 'clicks'));