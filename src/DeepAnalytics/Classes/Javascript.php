<?php

    namespace DeepAnalytics\Classes;

    class Javascript
    {
        /**
         * The Display ID to target in the HTML DOM
         *
         * @var string
         */
        public $DisplayID;

        /**
         * The route for the backend-API handler
         *
         * @var string
         */
        public $ApiHandlerRoute;

        /**
         * The colors to apply to the chart lines
         *
         * @var string[]
         */
        public $ChartColors;

        /**
         * The grid color lines
         *
         * @var string
         */
        public $GridlineColor;

        public function __construct()
        {
            $this->ChartColors = [];
        }

        /**
         * Generates the Javascript code for DeepAnalytics
         *
         * @return string
         */
        public function generateCode(): string
        {
            $FilePath = __DIR__ . DIRECTORY_SEPARATOR . 'template.js';
            $FileContents = file_get_contents($FilePath);

            $FileContents = str_ireplace('/**%DISPLAY_ID%*/', $this->DisplayID, $FileContents);
            $FileContents = str_ireplace('/**%API_HANDLER_ROUTE%*/', $this->ApiHandlerRoute, $FileContents);
            $FileContents = str_ireplace('/**%CHAT_COLORS%*/', json_encode($this->ChartColors, JSON_UNESCAPED_SLASHES), $FileContents);
            $FileContents = str_ireplace('/**%GRIDLINES_COLOR%*/', $this->GridlineColor, $FileContents);

            return $FileContents;
        }
    }