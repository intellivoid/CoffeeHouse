<?php


    namespace CoffeeHouse\Abstracts\CoreNLP;

    /**
     * Class TimexDurationType
     * @package CoffeeHouse\Abstracts\CoreNLP
     */
    abstract class TimexDurationType
    {
        /**
         * Begin statements
         */

        const Date = "P";

        const Time = "PT";

        /**
         * Requires reference time
         */

        const SeveralDays = "PXD";

        const SeveralMonths = "PXM";

        const SeveralYears = "PXY";

        const SeveralWeeks = "PXW";

        const SeveralSeconds = "PTXS";

        const SeveralMinutes = "PTXM";

        const SeveralHours = "PTXH";

        const SeveralMilliseconds = "PTX.XS";

    }