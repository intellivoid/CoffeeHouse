<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;

    /**
     * Class Sentiment
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class Sentiment
    {
        const VeryNegative = "very_negative";

        const Negative = "negative";

        const Neutral = "neutral";

        const Positive = "positive";

        const VeryPositive = "very_positive";

        const Unknown = "unknown";
    }