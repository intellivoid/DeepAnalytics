<?php


    namespace DeepAnalytics;

    use acm\acm;
    use DeepAnalytics\Objects\HourlyData;
    use DeepAnalytics\Objects\MonthlyData;
    use Exception;
    use MongoDB\BSON\ObjectId;
    use MongoDB\Client;
    use MongoDB\Database;
    use MongoDB\Driver\Exception\BulkWriteException;
    use MongoDB\Model\BSONDocument;

    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'Date.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'HourlyData.php');
    include_once(__DIR__ . DIRECTORY_SEPARATOR . 'Objects' . DIRECTORY_SEPARATOR . 'MonthlyData.php');

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

        /**
         * Tallies an hourly rating
         *
         * @param string $collection
         * @param string $name
         * @param int $reference_id
         * @param int $amount
         * @param int|null $year
         * @param int|null $month
         * @param int|null $day
         * @param bool $throw_dup
         * @return HourlyData
         */
        public function tallyHourly(string $collection, string $name, int $reference_id=null, int $amount=1,
                                    int $year=null, int $month=null, int $day=null, bool $throw_dup=false): HourlyData
        {
            $HourlyData = new HourlyData($year, $month, $day);
            $HourlyData->ReferenceID = $reference_id;
            $HourlyData->Name = $name;

            $Collection = $this->Database->selectCollection($collection . '_hourly');
            $Document = null;

            if(is_null($reference_id))
            {
                $Document = $Collection->findOne([
                    "stamp" => $HourlyData->Stamp,
                    "name" => $name
                ]);
            }
            else
            {
                $Document = $Collection->findOne([
                    "stamp" => $HourlyData->Stamp,
                    "name" => $name,
                    "reference_id" => $reference_id
                ]);
            }

            if(is_null($Document))
            {
                if(is_null($reference_id))
                {
                    $reference_id = 0;
                }

                $Collection->createIndex(
                    [
                        "stamp" => 1,
                        "name" => 1,
                        "reference_id" => 1
                    ],
                    [
                        "unique" => true
                    ]
                );

                $HourlyData->tally($amount);
                $HourlyDataDocument = $HourlyData->toArray();
                unset($HourlyDataDocument["id"]);

                try
                {
                    $Document = $Collection->insertOne($HourlyDataDocument);
                }
                catch(BulkWriteException $bulkWriteException)
                {
                    // Handle duplicate error
                    if($bulkWriteException->getCode() == 11000)
                    {
                        if($throw_dup)
                        {
                            throw $bulkWriteException;
                        }

                        return $this->tallyHourly(
                            $collection, $name, $reference_id, $amount=1,
                            $year, $month, $day, true
                        );
                    }
                }

                $HourlyData->ID = (string)$Document->getInsertedId();
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
            }

            return $HourlyData;
        }

        /**
         * @param string $collection
         * @param string $name
         * @param int|null $reference_id
         * @param int $limit
         * @return array
         */
        public function getHourlyDataRange(string $collection, string $name, int $reference_id=null, $limit=100): array
        {
            $Collection = $this->Database->selectCollection($collection . '_hourly');

            if(is_null($reference_id))
            {
                $reference_id = 0;
            }

            $Cursor = $Collection->find(
                [
                    'name' => $name,
                    'reference_id' => $reference_id
                ],
                [
                    'projection' => [
                        '_id' => 1,
                        'stamp' => 1,
                        'date' => 1
                    ],
                    'limit' => $limit
                ]
            );

            $Results = [];

            /** @var BSONDocument $document */
            foreach($Cursor as $document)
            {
                $DocumentArray = (array)$document->jsonSerialize();
                $DateArray = (array)$DocumentArray['date']->jsonSerialize();

                $Results[$DocumentArray['stamp']] = array(
                    'id' => (string)$DocumentArray['_id'],
                    'date' => $DateArray
                );
            }

            return $Results;
        }


        /**
         * Tallies a monthly rating
         *
         * @param string $collection
         * @param string $name
         * @param int $reference_id
         * @param int $amount
         * @param int|null $year
         * @param int|null $month
         * @param bool $throw_dup
         * @return MonthlyData
         */
        public function tallyMonthly(string $collection, string $name, int $reference_id=null, int $amount=1,
                                    int $year=null, int $month=null, bool $throw_dup=false): MonthlyData
        {
            $MonthlyData = new MonthlyData($year, $month);
            $MonthlyData->ReferenceID = $reference_id;
            $MonthlyData->Name = $name;

            $Collection = $this->Database->selectCollection($collection . '_monthly');
            $Document = null;

            if(is_null($reference_id))
            {
                $Document = $Collection->findOne([
                    "stamp" => $MonthlyData->Stamp,
                    "name" => $name
                ]);
            }
            else
            {
                $Document = $Collection->findOne([
                    "stamp" => $MonthlyData->Stamp,
                    "name" => $name,
                    "reference_id" => $reference_id
                ]);
            }

            if(is_null($Document))
            {
                if(is_null($reference_id))
                {
                    $reference_id = 0;
                }

                $Collection->createIndex(
                    [
                        "stamp" => 1,
                        "name" => 1,
                        "reference_id" => 1
                    ],
                    [
                        "unique" => true
                    ]
                );

                $MonthlyData->tally($amount);
                $MonthlyDataDocument = $MonthlyData->toArray();
                unset($MonthlyDataDocument["id"]);

                try
                {
                    $Document = $Collection->insertOne($MonthlyDataDocument);
                }
                catch(BulkWriteException $bulkWriteException)
                {
                    // Handle duplicate error
                    if($bulkWriteException->getCode() == 11000)
                    {
                        if($throw_dup)
                        {
                            throw $bulkWriteException;
                        }

                        return $this->tallyMonthly(
                            $collection, $name, $reference_id, $amount=1,
                            $year, $month, true
                        );
                    }
                }

                $MonthlyData->ID = (string)$Document->getInsertedId();
            }
            else
            {
                $MonthlyData = Utilities::BSONDocumentToMonthlyData($Document);

                $MonthlyData->tally($amount);
                $MonthlyData->LastUpdated = (int)time();
                $MonthlyDataDocument = $MonthlyData->toArray();
                unset($MonthlyDataDocument["id"]);

                $Collection->updateOne(
                    ['_id' => new ObjectID($MonthlyData->ID)],
                    ['$set' => $MonthlyDataDocument]
                );
            }

            return $MonthlyData;
        }
    }