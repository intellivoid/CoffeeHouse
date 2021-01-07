<?php


    namespace CoffeeHouse\Classes;


    /**
     * Class Validation
     * @package CoffeeHouse\Classes
     */
    class Validation
    {
        /**
         * Determines if the message is valid or not
         *
         * @param string $input
         * @return bool
         */
        public static function message(string $input): bool
        {
            if(strlen($input) > 5000)
            {
                return false;
            }

            return true;
        }

        /**
         * Determines if the langauge code is supported for Google Translate
         *
         * @param string $input
         * @return bool
         */
        public static function googleTranslateSupported(string $input): bool
        {
            $supported_languages = [
                "af",
                "sq",
                "am",
                "ar",
                "hy",
                "az",
                "eu",
                "be","
                bn","
                bs","
                bg","
                ca","
                ceb","
                zh-cn","
                zh-tw","
                co","
                hr","
                cs","
                da","
                nl","
                eo","
                et","
                fi","
                fr","
                fy","
                gl","
                ka","
                de","
                el","
                gu","
                ht","
                ha","
                haw","
                he","
                hi","
                hmn","
                hu","
                is","
                ig","
                id","
                ga","
                it","
                ja","
                jv","
                kn","
                kk","
                km","
                rw","
                ko","
                ku","
                ky","
                lo","
                la","
                lv","
                lt","
                lb","
                mk","
                mg","
                ms","
                ml","
                mt","
                mi","
                mr","
                mn","
                my","
                ne","
                no","
                ny","
                or","
                ps","
                fa","
                pl","
                pt","
                pa","
                ro","
                ru","
                sm","
                gd","
                sr","
                st","
                sn","
                sd","
                si","
                sk","
                sl","
                so","
                es","
                su","
                sw","
                sv","
                tl","
                tg","
                ta","
                tt","
                te","
                th","
                tr","
                tk","
                uk","
                ur","
                ug","
                uz","
                vi","
                cy","
                xh","
                yi","
                yo","
                zu",
                "la"
            ];

            if(in_array(strtolower($input), $supported_languages))
            {
                return true;
            }

            return false;
        }
    }