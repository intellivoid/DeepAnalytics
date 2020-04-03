<?php


    namespace DeepAnalytics;

    use acm\acm;
    use Exception;
    use MongoDB\Client;
    use MongoDB\Database;

    if(class_exists('MongoDB\Client') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'MongoDB' . DIRECTORY_SEPARATOR . 'MongoDB.php');
    }

    if(class_exists('acm\acm') == false)
    {
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'acm' . DIRECTORY_SEPARATOR . 'acm.php');
    }

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'AutoConfig.php');

    /**
     * Class DeepAnalytics
     * @package DeepAnalytics
     */
    class DeepAnalytics
    {
        /**
         * @var acm
         */
        private $acm;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var Client
         */
        private $MongoDB_Client;

        /**
         * @var Database
         */
        private $Database;

        /**
         * DeepAnalytics constructor.
         * @throws Exception
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'DeepAnalytics');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('DeepAnalytics');

            $this->MongoDB_Client = new Client(
                "mongodb://" . $this->DatabaseConfiguration['Host'] . ":" . $this->DatabaseConfiguration['Port'],
                array(
                    "username" => $this->DatabaseConfiguration['Username'],
                    "password" => $this->DatabaseConfiguration['Password']
                )
            );

            $this->Database = $this->MongoDB_Client->selectDatabase($this->DatabaseConfiguration['Database']);
        }
    }