<?php

    /** @noinspection PhpUndefinedClassInspection */


    namespace CoffeeHouse;

    use acm\acm;
    use CoffeeHouse\Classes\ServerInterface;
    use CoffeeHouse\Managers\ChatDialogsManager;
    use CoffeeHouse\Managers\ForeignSessionsManager;
    use CoffeeHouse\Managers\GeneralizedClassificationManager;
    use CoffeeHouse\Managers\LanguagePredictionCacheManager;
    use CoffeeHouse\Managers\LargeGeneralizedClassificationManager;
    use CoffeeHouse\Managers\SpamPredictionCacheManager;
    use CoffeeHouse\Managers\UserSubscriptionManager;
    use CoffeeHouse\NaturalLanguageProcessing\LanguagePrediction;
    use CoffeeHouse\NaturalLanguageProcessing\SpamPrediction;
    use DeepAnalytics\DeepAnalytics;
    use Exception;
    use mysqli;
    use ppm\ppm;

    require_once(__DIR__ . DIRECTORY_SEPARATOR . 'AutoConfig.php');

    /**
     * Class CoffeeHouse
     * @package CoffeeHouse
     */
    class CoffeeHouse
    {
        /**
         * @var mysqli
         */
        private $database;

        /**
         * @var mixed
         */
        private $DatabaseConfiguration;

        /**
         * @var acm
         * @noinspection PhpUndefinedClassInspection
         */
        private $acm;

        /**
         * @var ForeignSessionsManager
         */
        private $ForeignSessionsManager;

        /**
         * @var ChatDialogsManager
         */
        private $ChatDialogsManager;

        /**
         * @var UserSubscriptionManager
         */
        private $UserSubscriptionManager;

        /**
         * @var DeepAnalytics
         */
        private $DeepAnalytics;

        /**
         * @var mixed
         */
        private $ServerConfiguration;

        /**
         * @var ServerInterface
         */
        private $ServerInterface;

        /**
         * @var SpamPrediction
         */
        private $SpamPrediction;

        /**
         * @var SpamPredictionCacheManager
         */
        private $SpamPredictionCacheManager;

        /**
         * @var GeneralizedClassificationManager
         */
        private $GeneralizedClassificationManager;

        /**
         * @var LanguagePrediction
         */
        private $LanguagePrediction;

        /**
         * @var LanguagePredictionCacheManager
         */
        private $LanguagePredictionCacheManager;

        /**
         * @var LargeGeneralizedClassificationManager
         */
        private $LargeGeneralizedClassificationManager;

        /**
         * CoffeeHouse constructor.
         * @throws Exception
         * @noinspection PhpUndefinedClassInspection
         */
        public function __construct()
        {
            $this->acm = new acm(__DIR__, 'CoffeeHouse');
            $this->DatabaseConfiguration = $this->acm->getConfiguration('Database');
            $this->ServerConfiguration = $this->acm->getConfiguration('CoffeeHouseServer');
            $this->database = null;

            $this->ForeignSessionsManager = new ForeignSessionsManager($this);
            $this->ChatDialogsManager = new ChatDialogsManager($this);
            $this->UserSubscriptionManager = new UserSubscriptionManager($this);
            $this->SpamPredictionCacheManager = new SpamPredictionCacheManager($this);
            $this->LanguagePredictionCacheManager = new LanguagePredictionCacheManager($this);
            $this->GeneralizedClassificationManager = new GeneralizedClassificationManager($this);
            $this->LargeGeneralizedClassificationManager = new LargeGeneralizedClassificationManager($this);
            $this->ServerInterface = new ServerInterface($this);
            $this->SpamPrediction = new SpamPrediction($this);
            $this->LanguagePrediction = new LanguagePrediction($this);
            $this->DeepAnalytics = new DeepAnalytics();
        }

        /**
         * @return mysqli
         */
        public function getDatabase()
        {
            if($this->database == null)
            {
                $this->database = new mysqli(
                    $this->DatabaseConfiguration['Host'],
                    $this->DatabaseConfiguration['Username'],
                    $this->DatabaseConfiguration['Password'],
                    $this->DatabaseConfiguration['Name'],
                    $this->DatabaseConfiguration['Port']
                );
            }

            return $this->database;
        }

        /**
         * @return ForeignSessionsManager
         */
        public function getForeignSessionsManager(): ForeignSessionsManager
        {
            return $this->ForeignSessionsManager;
        }

        /**
         * @return ChatDialogsManager
         */
        public function getChatDialogsManager(): ChatDialogsManager
        {
            return $this->ChatDialogsManager;
        }


        /**
         * @return mixed
         * @noinspection PhpUnused
         */
        public function getDatabaseConfiguration()
        {
            return $this->DatabaseConfiguration;
        }

        /**
         * @return UserSubscriptionManager
         * @noinspection PhpUnused
         */
        public function getUserSubscriptionManager(): UserSubscriptionManager
        {
            return $this->UserSubscriptionManager;
        }

        /**
         * @return DeepAnalytics
         */
        public function getDeepAnalytics(): DeepAnalytics
        {
            return $this->DeepAnalytics;
        }

        /**
         * @return mixed
         */
        public function getServerConfiguration()
        {
            return $this->ServerConfiguration;
        }

        /**
         * @return ServerInterface
         */
        public function getServerInterface(): ServerInterface
        {
            return $this->ServerInterface;
        }

        /**
         * @return SpamPredictionCacheManager
         */
        public function getSpamPredictionCacheManager(): SpamPredictionCacheManager
        {
            return $this->SpamPredictionCacheManager;
        }

        /**
         * @return SpamPrediction
         */
        public function getSpamPrediction(): SpamPrediction
        {
            return $this->SpamPrediction;
        }

        /**
         * @return GeneralizedClassificationManager
         */
        public function getGeneralizedClassificationManager(): GeneralizedClassificationManager
        {
            return $this->GeneralizedClassificationManager;
        }

        /**
         * @return LanguagePrediction
         * @noinspection PhpUnused
         */
        public function getLanguagePrediction(): LanguagePrediction
        {
            return $this->LanguagePrediction;
        }

        /**
         * @return LanguagePredictionCacheManager
         * @noinspection PhpUnused
         */
        public function getLanguagePredictionCacheManager(): LanguagePredictionCacheManager
        {
            return $this->LanguagePredictionCacheManager;
        }

        /**
         * @return LargeGeneralizedClassificationManager
         */
        public function getLargeGeneralizedClassificationManager(): LargeGeneralizedClassificationManager
        {
            return $this->LargeGeneralizedClassificationManager;
        }

    }