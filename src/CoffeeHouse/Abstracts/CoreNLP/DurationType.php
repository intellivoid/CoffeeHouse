<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;

    /**
     * Class DurationType
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class DurationType
    {

        const Date = "DATE";

        const Time = "TIME";

        const SeveralDays = "SEVERAL_DAYS";

        const SeveralMonths = "SEVERAL_MONTHS";

        const SeveralYears = "SEVERAL_YEARS";

        const SeveralWeeks = "SEVERAL_WEEKS";

        const SeveralSeconds = "SEVERAL_SECONDS";

        const SeveralMinutes = "SEVERAL_MINUTES";

        const SeveralHours = "SEVERAL_HOURS";

        const SeveralMilliseconds = "SEVERAL_MILLISECONDS";

        const Unknown = "UNKNOWN";
    }