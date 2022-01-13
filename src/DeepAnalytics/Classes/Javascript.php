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

        /**
         * The icon for the left pagination button
         *
         * @var string
         */
        public $PaginationLeftIcon;

        /**
         * The icon for the right pagination button
         *
         * @var string
         */
        public $PaginationRightIcon;

        /**
         * The spinning icon to display when loading
         *
         * @var string
         */
        public $SpinnerIcon;

        /**
         * Extra attributes to append to the TabPane class
         *
         * @var string
         */
        public $TabPaneExtras;

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

            $FileContents = str_ireplace('/**%DISPLAY_ID%*/', (string)$this->DisplayID, $FileContents);
            $FileContents = str_ireplace('/**%API_HANDLER_ROUTE%*/', (string)$this->ApiHandlerRoute, $FileContents);
            $FileContents = str_ireplace('/**%CHAT_COLORS%*/', json_encode($this->ChartColors, JSON_UNESCAPED_SLASHES), $FileContents);
            $FileContents = str_ireplace('/**%GRIDLINES_COLOR%*/', (string)$this->GridlineColor, $FileContents);
            $FileContents = str_ireplace('/**%PAGINATION_LEFT%*/', (string)$this->PaginationLeftIcon, $FileContents);
            $FileContents = str_ireplace('/**%PAGINATION_RIGHT%*/', (string)$this->PaginationRightIcon, $FileContents);
            $FileContents = str_ireplace('/**%SPINNER_ICON%*/', (string)$this->SpinnerIcon, $FileContents);
            $FileContents = str_ireplace('/**%TAB_PANE_EXTRAS%*/', (string)$this->TabPaneExtras, $FileContents);

            return $FileContents;
        }
    }