<?php


    namespace CoffeeHouse\Abstracts;


    /**
     * The supported modules that runs on CoffeeHouse-Utils
     *
     * Class ServerInterfaceModule
     * @package CoffeeHouse\Abstracts
     */
    abstract class ServerInterfaceModule
    {
        /**
         * Ping Service Module of CoffeeHouse-Utils
         */
        const PingService = "PING_SERVICE";

        /**
         * Spam Detection Module of CoffeeHouse-Utils
         */
        const SpamPrediction = "SPAM_PREDICTION";

        /**
         * NSFW Image Prediction Module of the CoffeeHouse-Utils
         */
        const NsfwPrediction = "NSFW_CLASSIFIER";

        /**
         * Translation Service Module of the CoffeeHouse-Utils
         */
        const TranslateService = "TRANSLATE_SERVICE";

        /**
         * CoreNLP
         */
        const CoreNLP = "CORE_NLP";

        /**
         * Emotion Prediction Module of the CoffeeHouse-Utils
         */
        const EmotionPrediction = "EMOTION_PREDICTION";

        /**
         * Language Prediction Module of the CoffeeHouse-Utils
         */
        const LanguagePrediction = "LANGUAGE_PREDICTION";
    }