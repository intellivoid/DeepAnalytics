<?php
    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    $FetchedData = array();
    $Labels =  ["Clicks", "Request", "Downloads"];

    $hourly_clicks_data = $DeepAnalytics->getHourlyData('example','clicks');
    foreach($hourly_clicks_data->getData(true) as $key => $value)
    {
        $Date = \DeepAnalytics\Utilities::generateFullHourStamp($hourly_clicks_data->Date, $key);
        $FetchedData[$Date]['clicks'] = $value;
    }

    $hourly_downloads_data = $DeepAnalytics->getHourlyData('example', 'downloads');
    foreach($hourly_downloads_data->getData(true) as $key => $value)
    {
        $Date = \DeepAnalytics\Utilities::generateFullHourStamp($hourly_downloads_data->Date, $key);
        $FetchedData[$Date]['downloads'] = $value;
    }

    $hourly_requests_data = $DeepAnalytics->getHourlyData('example', 'requests');
    foreach($hourly_requests_data->getData(true) as $key => $value)
    {
        $Date = \DeepAnalytics\Utilities::generateFullHourStamp($hourly_requests_data->Date, $key);
        $FetchedData[$Date]['requests'] = $value;
    }

    $Data = [];

    foreach($FetchedData as $key => $value)
    {
        $Data[] = array(
            'hour' => $key,
            'clicks' => $value['clicks'],
            'downloads' => $value['downloads'],
            'requests' => $value['requests']
        );
    }

    print("<pre>" . json_encode($FetchedData, JSON_PRETTY_PRINT) . "</pre>");
?>
<html>
    <header>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <title>DeepAnal</title>
    </header>
    <body>

        <div id="hourly_data" style="height: 250px;"></div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <script>
            new Morris.Line({
                // ID of the element in which to draw the chart.
                element: 'hourly_data',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: <?PHP print(json_encode($Data, JSON_PRETTY_PRINT)); ?>,
                // The name of the data record attribute that contains x-values.
                xkey: 'hour',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['clicks', 'requests', 'downloads'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ["Clicks", "Requests", "Downloads"]
            });
        </script>
    </body>


</html>