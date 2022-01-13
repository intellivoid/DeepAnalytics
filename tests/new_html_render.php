<?php

    use DeepAnalytics\Classes\API;
use DeepAnalytics\Classes\Javascript;
use DeepAnalytics\DeepAnalytics;

    /**
     * Import packages
     */
    require 'ppm';
    require 'net.intellivoid.deepanalytics';

    /**
     * Create DeepAnalytics Instance
     */
    $DeepAnalytics = new DeepAnalytics();

    /**
     * API Handler
     */
    API::setNamesLocale([
        'clicks' => 'User Clicks',
        'downloads' => 'File Downloads',
        'requests' => 'HTTP Requests'
    ]);

    $api_results = API::handle($DeepAnalytics, 'example', ['clicks', 'downloads', 'requests']);
    if($api_results !== null)
    {
        header('Content-Type: text/json');
        print($api_results);
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
        <title>Analytics Example</title>
    </head>
    <body>

        <main role="main" class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Example Analytics</h4>
                            <div id="deepanalytics_viewer">
                                <span>Loading</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <script>
            <?PHP
                $Javascript = new Javascript();
                $Javascript->DisplayID = 'deepanalytics_viewer';
                $Javascript->ApiHandlerRoute = 'http://127.0.0.1:5008/deepanalytics/new_html_render.php';
                $Javascript->ChartColors = ['#5468da', '#ffbb44', '#67a8e4', '#4ac18e', '#ea553d', '#3bc3e9', '#ea553d', '#e83e8c', '#007bff', '#20c997', '#ffc107', '#dc3545', '#6f42c1'];
                $Javascript->GridlineColor = '#1a2036';

                print($Javascript->generateCode());
            ?>
        </script>
    </body>
</html>