<?php


    namespace DeepAnalytics;

    use acm\acm;
    use DeepAnalytics\Objects\HourlyData;
    use Exception;
    use MongoDB\BSON\ObjectId;
    use MongoDB\Client;
    use MongoDB\Database;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'Date.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'HourlyData.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'Month.php');

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Utilities.php');

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
            $this->acm = new acm(__DIR__, 'deep_analytics');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('MongoDB');

            $this->MongoDB_Client = new Client(
                "mongodb://" . $this->DatabaseConfiguration['Host'] . ":" . $this->DatabaseConfiguration['Port'],
                array(
                    "username" => $this->DatabaseConfiguration['Username'],
                    "password" => $this->DatabaseConfiguration['Password']
                )
            );

            $this->Database = $this->MongoDB_Client->selectDatabase($this->DatabaseConfiguration['Database']);
        }

        public function tallyHourly(string $collection, int $reference_id, string $name, int $amount=1,
                                    int $year=null, int $month=null, $day=null): HourlyData
        {
            $HourlyData = new HourlyData($year, $month, $day);
            $HourlyData->ReferenceID = $reference_id;
            $HourlyData->Name = $name;

            $Collection = $this->Database->selectCollection($collection . '_hourly');
            $Document = $Collection->findOne(["stamp" => $HourlyData->Stamp, "name"=>$name]);

            if(is_null($Document))
            {
                $HourlyData->tally($amount);
                $HourlyDataDocument = $HourlyData->toArray();
                unset($HourlyDataDocument["id"]);
                $Document = $Collection->insertOne($HourlyDataDocument);

                $HourlyData->ID = (string)$Document->getInsertedId();

                return $HourlyData;
            }
            else
            {
                $HourlyData = Utilities::BSONDocumentToHourlyData($Document);

                $HourlyData->tally($amount);
                $HourlyData->LastUpdated = (int)time();
                $HourlyDataDocument = $HourlyData->toArray();
                unset($HourlyDataDocument["id"]);

                $Collection->updateOne(
                    ['_id' => new ObjectID($HourlyData->ID)],
                    ['$set' => $HourlyDataDocument]
                );

                return $HourlyData;
            }
        }
    }