<?php
    $Source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    include_once($Source . 'DeepAnalytics' . DIRECTORY_SEPARATOR . 'DeepAnalytics.php');

    $DeepAnalytics = new \DeepAnalytics\DeepAnalytics();

    $FetchedData = array();
    $Labels =  ["Clicks", "Request", "Downloads"];

    $monthly_clicks_data = $DeepAnalytics->getMonthlyData('example','clicks');
    foreach($monthly_clicks_data->Data as $key => $value)
    {
        $FetchedData["$key:00"]['clicks'] = $value;
    }

    $monthly_downloads_data = $DeepAnalytics->getMonthlyData('example', 'downloads');
    foreach($monthly_downloads_data->Data as $key => $value)
    {
        $FetchedData["$key:00"]['downloads'] = $value;
    }

    $monthly_requests_data = $DeepAnalytics->getMonthlyData('example', 'requests');
    foreach($monthly_requests_data->Data as $key => $value)
    {
        $FetchedData["$key:00"]['requests'] = $value;
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