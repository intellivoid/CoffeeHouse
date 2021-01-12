<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;

    /**
     * Class Sentiment
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class Sentiment
    {
        const VeryNegative = "VERY_NEGATIVE";

        const Negative = "NEGATIVE";

        const Neutral = "NEUTRAL";

        const Positive = "POSITIVE";

        const VeryPositive = "VERY_POSITIVE";

        const Unknown = "UNKNOWN";
    }