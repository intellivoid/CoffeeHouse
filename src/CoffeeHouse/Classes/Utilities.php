<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Abstracts\CoreNLP\DurationDateTypes;
    use CoffeeHouse\Abstracts\CoreNLP\DurationTimeTypes;
    use CoffeeHouse\Abstracts\CoreNLP\DurationType;
    use CoffeeHouse\Abstracts\CoreNLP\NamedEntity;
    use CoffeeHouse\Abstracts\CoreNLP\Sentiment;
    use CoffeeHouse\Abstracts\CoreNLP\TimexDateDuration;
    use CoffeeHouse\Abstracts\CoreNLP\TimexDurationType;
    use CoffeeHouse\Abstracts\CoreNLP\TimexTimeDuration;
    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Exceptions\CannotDetermineDurationTypeException;
    use CoffeeHouse\Exceptions\InvalidDateException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Exceptions\InvalidTimexDateDurationException;
    use CoffeeHouse\Exceptions\InvalidTimexDurationTypeException;
    use CoffeeHouse\Exceptions\InvalidTimexFormatException;
    use CoffeeHouse\Exceptions\InvalidTimexTimeDurationException;
    use CoffeeHouse\Exceptions\TimexDurationParseException;
    use CoffeeHouse\Objects\HttpResponse;
    use CoffeeHouse\Objects\ProcessedNLP\Types\DateType;
    use CoffeeHouse\Objects\ProcessedNLP\Types\TimeType;

    /**
     * Class Utilities
     * @package CoffeeHouse\Classes
     */
    class Utilities
    {

        /**
         * Raw HTTP Request
         *
         * @param string $url
         * @param $cookies
         * @param $parameters
         * @param null $headers
         * @return HttpResponse
         * @throws BotSessionException
         */
        public static function request(string $url, &$cookies, $parameters, $headers = null): HttpResponse
        {
            $ContextParameters  = array();
            $ContextParameters['http'] = array();

            // Process if it's a POST request or not
            if($parameters)
            {
                $ContextParameters['http']['method'] = 'POST';
                $ContextParameters['http']['content'] = http_build_query($parameters);
                $ContextParameters['http']['header'] = "Content-type: application/x-www-form-urlencoded\r\n";
            }
            else
            {
                $ContextParameters['http']['method'] = 'GET';
            }

            // Process the cookies
            if(!is_null($cookies) && count($cookies) > 0)
            {
                $CookieHeader = "Cookie: ";
                foreach($cookies as $Name => $Value)
                {
                    // TODO: Double check if it's supposed to be "NAME=VALUE;"
                    $CookieHeader .= $Value . ";";
                }
                $CookieHeader .= "\r\n";

                if(isset($ContextParameters['http']['header']))
                {
                    $ContextParameters['http']['header'] .= $CookieHeader;
                }
                else
                {
                    $ContextParameters['http']['header'] = $CookieHeader;
                }
            }

            // Process custom headers
            if(!is_null($headers))
            {
                foreach($headers as $HeaderName => $HeaderValue)
                {
                    $HeaderRow = $HeaderName . ': ' . $HeaderValue . "\r\n";

                    if(isset($ContextParameters['http']['header']))
                    {
                        $ContextParameters['http']['header'] .= $HeaderRow;
                    }
                    else
                    {
                        $ContextParameters['http']['header'] = $HeaderRow;
                    }
                }
            }

            // Establish the request stream
            $Context = stream_context_create($ContextParameters);
            $BufferStream = fopen($url, 'rb', false, $Context);
            if(!$BufferStream)
            {
                throw new BotSessionException(error_get_last());
            }
            $Response = stream_get_contents($BufferStream);


            // Accept new cookies
            if(!is_null($cookies))
            {
                foreach($http_response_header as $header)
                {
                    if (preg_match('@Set-Cookie: (([^=]+)=[^;]+)@i', $header, $matches))
                    {
                        $cookies[$matches[2]] = $matches[1];
                    }
                }
            }

            // Close the stream
            fclose($BufferStream);
            return new HttpResponse($cookies, $Response);
        }

        /**
         * @param $strings
         * @param $index
         * @return mixed
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function stringAtIndex($strings, $index): mixed
        {
            if(count($strings) > $index)
            {
                return $strings[$index];
            }

            return '';
        }

        /**
         * Replaces third party input
         *
         * @param string $input
         * @return string
         */
        public static function replaceThirdPartyMessages(string $input): string
        {
            $input = str_ireplace('cleverbot', 'Lydia', $input);
            $input = str_ireplace('clever bot', 'Lydia', $input);
            $input = str_ireplace('chelverbot', 'Lydia', $input);
            $input = str_ireplace('rollo carpenter', 'Zi Xing', $input);
            $input = str_ireplace('jabberwacky', 'Lydia', $input);
            $input = str_ireplace('clever', 'smart', $input);
            $input = str_ireplace('existor', 'Intellivoid', $input);

            return $input;
        }

        /**
         * Cleans the double spaces found in text
         *
         * @param string $input
         * @return string
         */
        public static function cleanInput_DS(string $input): string
        {
            return preg_replace('/\s+/', ' ', $input);
        }

        /**
         * Converts the input of a language to a ISO-639-1 Standard two letter code. This function
         * is pretty smart in terms of detecting the input appropriately.
         *
         * @param string $input
         * @return string
         * @throws InvalidLanguageException
         */
        public static function convertToISO6391(string $input): string
        {
            return match (strtolower($input)) {
                "аҧсуа бызшәа", "аҧсшәа", "abkhazian", "abk", "ab" => "ab",
                "afaraf", "afar", "aar", "aa" => "aa",
                "afrikaans", "afr", "af" => "af",
                "akan", "aka", "ak" => "ak",
                "albanian", "shqip", "alb", "sqi", "sq" => "sq",
                "amharic", "አማርኛ", "amh", "am" => "am",
                "arabic", "العربية", "ara", "ar" => "ar",
                "aragonese", "aragonés", "arg", "an" => "an",
                "armenian", "Հայերեն", "arm", "hye", "hy" => "hy",
                "assamese", "অসমীয়া", "asm", "as" => "as",
                "avaric", "авар мацӀ", "магӀарул мацӀ", "avar", "ava", "av" => "av",
                "avestan", "avesta", "ave", "ae" => "ae",
                "aymara", "aymar aru", "aym", "ay" => "ay",
                "azerbaijani", "azərbaycan dili", "تۆرکجه", "aze", "az" => "az",
                "bamanankan", "bambara", "bam", "bm" => "bm",
                "bashkir", "башҡорт теле", "bak", "ba" => "ba",
                "basque", "euskara", "euskera", "baq", "eus", "eu" => "eu",
                "belarusian", "беларуская мова", "bel", "be" => "be",
                "bengali", "bangla", "বাংলা", "ben", "bn" => "bn",
                "bihari languages", "bihari", "भोजपुरी", "bih", "bh" => "bh",
                "bislama", "bis", "bi" => "bi",
                "bosnian", "bosanski jezik", "bos", "bs" => "bs",
                "breton", "bre", "br" => "br",
                "bulgarian", "български език", "bul", "bg" => "bg",
                "burmese", "ဗမာစာ", "myanmar", "bur", "mya", "my" => "my",
                "catalan", "valencian", "català", "valencià", "cat", "ca" => "ca",
                "chamorro", "chamoru", "cha", "ch" => "ch",
                "chechen", "нохчийн мотт", "che", "ce" => "ce",
                "chichewa", "chewa", "nyanja", "chicheŵa", "chinyanja", "nya", "ny" => "ny",
                "chinese", "中文", "zhōngwén", "汉语", "漢語", "chi", "zho", "zh-cn", "zh" => "zh",
                "chuvash", "чӑваш чӗлхи", "chv", "cv" => "cv",
                "cornish", "kernewek", "cor", "kw" => "kw",
                "corsican", "corsu", "lingua corsa", "cos", "co" => "co",
                "cree", "ᓀᐦᐃᔭᐍᐏᐣ", "cre", "cr" => "cr",
                "croatian", "hrvatski jezik", "hrv", "hr" => "hr",
                "czech", "čeština", "český jazyk", "ces", "cze", "cs" => "cs",
                "danish", "dansk", "dan", "da" => "da",
                "divehi", "dhivehi", "maldivian", "ދިވެހި", "div", "dv" => "dv",
                "dutch", "flemish", "nederlands", "vlaams", "nld", "dut", "nl" => "nl",
                "dzongkha", "རྫོང་ཁ", "dzo", "dz" => "dz",
                "english", "eng", "en" => "en",
                "esperanto", "epo", "eo" => "eo",
                "estonian", "eesti", "eesti keel", "est", "et" => "et",
                "eʋegbe", "ewe", "ee" => "ee",
                "faroese", "føroyskt", "fao", "fo" => "fo",
                "fijian", "vosa vakaviti", "fij", "fj" => "fj",
                "finnish", "suomi", "suomen kieli", "fin", "fi" => "fi",
                "french", "français", "langue française", "fra", "fre", "fr" => "fr",
                "fulah", "fulfulde", "pulaar", "pular", "fula", "ful", "ff" => "ff",
                "galician", "galego", "glg", "gl" => "gl",
                "georgian", "ქართული", "kat", "geo", "ka" => "ka",
                "german", "deutsch", "deu", "ger", "de" => "de",
                "greek", "ελληνικά", "ell", "gre", "el" => "el",
                "guarani", "avañe'ẽ", "grn", "gn" => "gn",
                "Gujarati", "ગુજરાતી", "guj", "gu" => "gu",
                "haitian", "haitian creole", "kreyòl ayisyen", "hat", "ht" => "ht",
                "hausa", "هَوُسَ", "hau", "ha" => "ha",
                "hebrew", "עברית", "heb", "he" => "he",
                "herero", "her", "hz" => "hz",
                "hindi", "हिन्दी", "हिंदी", "hin", "hi" => "hi",
                "hiri motu", "hmo", "ho" => "ho",
                "hungarian", "magyar", "hun", "hu" => "hu",
                "interlingua", "ina", "ia" => "ia",
                "indonesian", "bahasa indonesia", "ind", "id" => "id",
                "interlingue", "occidental", "ile", "ie" => "ie",
                "irish", "gaeilge", "gle", "ga" => "ga",
                "igbo", "asụsụ igbo", "ibo", "ig" => "ig",
                "inupiaq", "iñupiaq", "iñupiatun", "ipk", "ik" => "ik",
                "ido", "io" => "io",
                "icelandic", "Íslenska", "ice", "isl", "is" => "is",
                "italian", "italiano", "ita", "it" => "it",
                "inuktitut", "ᐃᓄᒃᑎᑐᑦ", "iku", "iu" => "iu",
                "japanese", "日本語", "にほんご", "jpn", "ja" => "ja",
                "javanese", "ꦧꦱꦗꦮ", "basa jawa", "jav", "jv" => "jv",
                "kalaallisut", "greenlandic", "kalaallit oqaasii", "kal", "kl" => "kl",
                "kannada", "ಕನ್ನಡ", "kan", "kn" => "kn",
                "kanuri", "kau", "kr" => "kr",
                "kashmiri", "कश्मीरी", "كشميري", "kas", "ks" => "ks",
                "kazakh", "қазақ тілі", "kaz", "kk" => "kk",
                "central khmer", "cambodian", "khmer", "ខ្មែរ", "ខេមរភាសា", "ភាសាខ្មែរ", "khm", "km" => "km",
                "kinyarwanda", "ikinyarwanda", "kin", "rw" => "rw",
                "kirghiz", "kyrgyz", "Кыргызча", "Кыргыз тили", "kir", "ky" => "ky",
                "komi", "коми кыв", "kom", "kv" => "kv",
                "kongo", "kon", "kg" => "kg",
                "korean", "한국어", "kor", "ko" => "ko",
                "kurdish", "kurdî", "کوردی", "kur", "ku" => "ku",
                "Kuanyama", "Kwanyama", "kua", "kj" => "kj",
                "katin", "latine", "latina", "latin", "lat", "la" => "la",
                "luxembourgish", "letzeburgesch", "lëtzebuergesch", "ltz", "lb" => "lb",
                "ganda", "luganda", "lug", "lg" => "lg",
                "limburgan", "limburger", "limburgish", "limburgs", "lim", "li" => "li",
                "lingala", "lingála", "lin", "ln" => "ln",
                "lao", "ພາສາລາວ", "lo" => "lo",
                "lithuanian", "lietuvių kalba", "lit", "lt" => "lt",
                "luba-shaba", "luba-katanga", "kiluba", "lub", "lu" => "lu",
                "Latvian", "latviešu valoda", "lav", "lv" => "lv",
                "manx", "gaelg", "gailck", "glv", "gv" => "gv",
                "Macedonian", "македонски јазик", "mkd", "mac", "mk" => "mk",
                "Malagasy", "fiteny malagasy", "mlg", "mg" => "mg",
                "malay", "bahasa melayu", "بهاس ملايو", "msa", "may", "ms" => "ms",
                "malayalam", "മലയാളം", "mal", "ml" => "ml",
                "maltese", "malti", "mlt", "mt" => "mt",
                "maori", "māori", "te reo māori", "mri", "mao", "mi" => "mi",
                "marāṭhī", "marathi", "मराठी", "mar", "mr" => "mr",
                "marshallese", "kajin m̧ajeļ", "mah", "mh" => "mh",
                "mongolian", "mонгол хэл", "mon", "mn" => "mn",
                "nauru", "nauruan", "dorerin naoero", "nau", "na" => "na",
                "navajo", "navaho", "diné bizaad", "nav", "nv" => "nv",
                "north ndebele", "isindebele", "ndebele", "nde", "nd" => "nd",
                "nepali", "नेपाली", "nep", "ne" => "ne",
                "ndonga", "owambo", "ndo", "ng" => "ng",
                "norwegian bokmål", "norsk bokmål", "nob", "nb" => "nb",
                "norwegian nynorsk", "norsk nynorsk", "nno", "nn" => "nn",
                "norwegian", "nor", "no" => "no",
                "sichuan yi", "nuosu", "nuosuhxop", "ꆈꌠ꒿", "ꆈꌠ꒿ nuosuhxop", "iii", "ii" => "ii",
                "south ndebele", "nbl", "nr" => "nr",
                "occitan", "lenga d'òc", "oci", "oc" => "oc",
                "Ojibwa", "ᐊᓂᔑᓈᐯᒧᐎᓐ", "oji", "oj" => "oj",
                "church slavic", "old slavonic", "church slavonic", "old bulgarian", "old church slavonic", "ѩзыкъ словѣньскъ", "chu", "cu" => "cu",
                "oromo", "afaan oromoo", "orm", "om" => "om",
                "oriya", "odia", "ଓଡ଼ିଆ", "ori", "or" => "or",
                "ossetian", "ossetic", "ирон æвзаг", "oss", "os" => "os",
                "punjabi", "panjabi", "ਪੰਜਾਬੀ", "پنجابی", "pan", "pa" => "pa",
                "pali", "pāli", "पालि", "पाळि", "pli", "pi" => "pi",
                "persian", "farsi", "فارسی", "per", "far", "fa" => "fa",
                "polish", "język polski", "polszczyzna", "pol", "pl" => "pl",
                "pashto", "pushto", "پښتو", "pus", "ps" => "ps",
                "portuguese", "português", "por", "pt" => "pt",
                "quechua", "runa simi", "kichwa", "que", "qu" => "qu",
                "romansh", "rumantsch grischun", "roh", "rm" => "rm",
                "rundi", "kirundi", "ikirundi", "run", "rn" => "rn",
                "romanian", "moldavian", "moldovan", "română", "ron", "rum", "ro" => "ro",
                "russian", "русский", "rus", "ru" => "ru",
                "sanskrit", "saṃskṛta", "संस्कृतम्", "san", "sa" => "sa",
                "sardinian", "sardu", "srd", "sc" => "sc",
                "sindhi", "सिन्धी", "سنڌي، سندھی", "snd", "sd" => "sd",
                "northern sami", "davvisámegiella", "sme", "se" => "se",
                "samoan", "gagana fa'a samoa", "smo", "sm" => "sm",
                "sango", "yângâ tî sängö", "sag", "sg" => "sg",
                "serbian", "српски језик", "srp", "sr" => "sr",
                "gaelic", "scottish gaelic", "gàidhlig", "gla", "gd" => "gd",
                "shona", "chishona", "sna", "sn" => "sn",
                "sinhala", "sinhalese", "සිංහල", "sin", "si" => "si",
                "slovak", "slovenčina", "slovenský jazyk", "slk", "slo", "sk" => "sk",
                "slovenian", "slovene", "slovenski jezik", "slovenščina", "slv", "sl" => "sl",
                "somali", "soomaaliga", "af soomaali", "som", "so" => "so",
                "southern sotho", "sesotho", "sot", "st" => "st",
                "spanish", "castilian", "español", "spa", "es" => "es",
                "sundanese", "basa sunda", "sun", "su" => "su",
                "swahili", "swa", "sw" => "sw",
                "swazi", "swati", "siswati", "ssw", "ss" => "ss",
                "swedish", "svenska", "swe", "sv" => "sv",
                "tamil", "தமிழ்", "tam", "ta" => "ta",
                "telugu", "తెలుగు", "tel", "te" => "te",
                "tajik", "тоҷикӣ", "toçikī", "تاجیکی", "tgk", "tg" => "tg",
                "thai", "ไทย", "tha", "th" => "th",
                "tigrinya", "ትግርኛ", "tir", "ti" => "ti",
                "standard tibetan", "tibetan", "ོད་ཡིག", "bod", "tib", "bo" => "bo",
                "turkmen", "türkmen", "tүркмен", "tuk", "tk" => "tk",
                "tagalog", "wikang tagalog", "tgl", "tl" => "tl",
                "tswana", "setswana", "tsn", "tn" => "tn",
                "tongan", "tonga", "faka tonga", "ton", "to" => "to",
                "turkish", "türkçe", "tur", "tr" => "tr",
                "tsonga", "xitsonga", "tso", "ts" => "ts",
                "tatar", "татар теле", "tatar tele", "tat", "tt" => "tt",
                "twi", "tw" => "tw",
                "tahitian", "reo tahiti", "tah", "ty" => "ty",
                "uighur", "uyghur", "ئۇيغۇرچە", "uyghurche", "uig", "ug" => "ug",
                "ukrainian", "Українська", "ukr", "uk" => "uk",
                "urdu", "اردو", "urd", "ur" => "ur",
                "uzbek", "oʻzbek", "Ўзбек", "أۇزبېك", "uzb", "uz" => "uz",
                "venda", "tshivenḓa", "ven", "ve" => "ve",
                "vietnamese", "tiếng việt", "vie", "vi" => "vi",
                "volapük", "volapuk", "vol", "vo" => "vo",
                "walloon", "walon", "wln", "wa" => "wa",
                "welsh", "cymraeg", "cym", "wel", "cy" => "cy",
                "wolof", "wollof", "wol", "wo" => "wo",
                "western frisian", "frisian", "frysk", "fry", "fy" => "fy",
                "xhosa", "isixhosa", "xho", "xh" => "xh",
                "yiddish", "ייִדיש", "yid", "yi" => "yi",
                "yoruba", "yorùbá", "yor", "yo" => "yo",
                "zhuang", "chuang", "saɯ cueŋƅ", "saw cuengh", "zha", "za" => "za",
                "zulu", "isizulu", "zul", "zu" => "zu",
                default => throw new InvalidLanguageException("The given language '$input' is not supported"),
            };
        }

        /**
         * Converts a sentimental value to a string
         *
         * @param int $input
         * @return string
         */
        public static function sentimentValueToString(int $input): string
        {
            return match ($input) {
                0 => Sentiment::VeryNegative,
                1 => Sentiment::Negative,
                2 => Sentiment::Neutral,
                3 => Sentiment::Positive,
                4 => Sentiment::VeryPositive,
                default => Sentiment::Unknown,
            };
        }

        /**
         * Turns a sentimental string to a value
         *
         * @param string $input
         * @return int
         */
        public static function sentimentStringToValue(string $input): int
        {
            return match ($input) {
                Sentiment::VeryNegative => 0,
                Sentiment::Negative => 1,
                Sentiment::Neutral => 2,
                Sentiment::Positive => 3,
                Sentiment::VeryPositive => 4,
                default => -1,
            };
        }

        /**
         * Converts an XML object to an array
         *
         * @param $xmlObject
         * @return array
         */
        public static function xml2array($xmlObject): array
        {
            $out = array();
            foreach((array)$xmlObject as $index => $node)
                $out[$index] = (is_object($node)) ? self::xml2array($node) : $node;
            return $out;
        }

        /**
         * PHP Implementation of Java's startsWith() function
         *
         * @param string $haystack
         * @param string $needle
         * @return bool
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function startsWith(string $haystack, string $needle): bool
        {
            $length = strlen( $needle );
            return substr( $haystack, 0, $length ) === $needle;
        }

        /**
         * PHP Implementation of Java's endsWith() function
         *
         * @param string $haystack
         * @param string $needle
         * @return bool
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function endsWith(string $haystack, string $needle): bool
        {
            $length = strlen( $needle );
            if( !$length ) {
                return true;
            }
            return substr( $haystack, -$length ) === $needle;
        }

        /**
         * Creates a DateTime object from the Timex format
         *
         * @param string $input
         * @return DateType
         * @throws InvalidTimexFormatException
         */
        public static function getTimexDate(string $input): DateType
        {
            $date = new DateType();

            if((bool)preg_match('/\d\d\d\d-\d\d-\d\d/m', $input, $matches))
            {
                $date->Year = substr($matches[0], 0, 4);
                $date->Month = substr($matches[0], 5, 2);
                $date->Day = substr($matches[0], 8, 2);

                return $date;
            }
            elseif((bool)preg_match('/\d\d\d\d\d\d\d\d/m', $input, $matches))
            {
                $date->Year = substr($matches[0], 0, 4);
                $date->Month = substr($matches[0], 4, 2);
                $date->Day = substr($matches[0], 6, 2);

                return $date;
            }

            throw new InvalidTimexFormatException("The input '$input' is not a valid Timex expression");
        }

        /**
         * @param string $input
         * @return TimeType
         * @throws InvalidTimexFormatException
         */
        public static function getTimexTime(string $input): TimeType
        {
            $time = new TimeType();

            // Remove the TIMEX3 T value if there's any.
            if(strtolower(substr($input, 0, 1)) == "t")
            {
                $input = substr($input, 1, strlen($input));
            }

            /**
             * Matches the following time formats
             *
             * 01:00, 02:00, 13:00,
             * 1:00,  2:00, 13:01,
             * 23:59, 15:00,
             * 14:34:43, 01:00:00
             */
            if((bool)preg_match('/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/m', $input, $matches))
            {
                $parsed_unix_timestamp = strtotime($matches[0]);
                $time->Hour = date("H", $parsed_unix_timestamp);
                $time->Minute = date("i", $parsed_unix_timestamp);
                $time->Seconds = date("s", $parsed_unix_timestamp);

                return $time;
            }

            throw new InvalidTimexFormatException("The input '$input' is not a valid Timex expression");
        }

        /**
         * Gets the Unix Timestamp from a DateType and TimeType combination
         *
         * @param DateType $dateType
         * @param TimeType $timeType
         * @return int
         * @throws InvalidDateException
         */
        public static function getUnixTimestamp(DateType $dateType, TimeType $timeType): int
        {
            $date_format = $dateType->Year . "-" . $dateType->Month . "-" . $dateType->Day;
            $time_format = $timeType->Hour . ":" . $timeType->Minute . ":" . $timeType->Seconds;
            $parsed = strtotime($date_format . " " . $time_format);

            if($parsed == false)
                throw new InvalidDateException("The string '$parsed' cannot be parsed.");

            return (int)$parsed;
        }

        /**
         * Parses the ner type into a CoffeeHouse NerType
         *
         * @param string $input
         * @return string
         * @noinspection PhpPureAttributeCanBeAddedInspection
         */
        public static function parseNerType(string $input): string
        {
            return match (strtolower($input)) {
                "person" => NamedEntity::Person,
                "location" => NamedEntity::Location,
                "organization" => NamedEntity::Organization,
                "misc" => NamedEntity::Miscellaneous,
                "money" => NamedEntity::Money,
                "number" => NamedEntity::Number,
                "ordinal" => NamedEntity::Ordinal,
                "percent" => NamedEntity::Percent,
                "date" => NamedEntity::Date,
                "time" => NamedEntity::Time,
                "duration" => NamedEntity::Duration,
                "set" => NamedEntity::Set,
                "state_or_province" => NamedEntity::StateOrProvince,
                "country" => NamedEntity::Country,
                "nationality" => NamedEntity::Nationality,
                "religion" => NamedEntity::Religion,
                "title" => NamedEntity::JobTitle,
                "ideology" => NamedEntity::Ideology,
                "criminal_charge" => NamedEntity::CriminalCharge,
                "cause_of_death" => NamedEntity::CauseOfDeath,
                "handle" => NamedEntity::UsernameHandle,
                "email" => NamedEntity::Email,
                "city" => NamedEntity::City,
                default => NamedEntity::Unknown,
            };
        }

        /**
         * Parses the duration type from a TIMEX3 value format
         *
         * @param string $input
         * @return string
         * @throws CannotDetermineDurationTypeException
         */
        public static function parseTimexDurationType(string $input): string
        {
            $input = strtoupper($input);

            if(
                strlen($input) > 3 &&
                substr($input, 0, 3) !== "PTX" &&
                substr($input, 0, 2) == "PT" || substr($input, 0, 4) == "PT0."
            )
            {
                return TimexDurationType::Time;
            }

            if(strlen($input) == 6)
            {
                if($input == TimexDurationType::SeveralMilliseconds)
                    return TimexDurationType::SeveralMilliseconds;
            }

            if(strlen($input) == 4)
            {
                if($input == TimexDurationType::SeveralHours)
                    return TimexDurationType::SeveralHours;

                if($input == TimexDurationType::SeveralMinutes)
                    return TimexDurationType::SeveralMinutes;

                if($input == TimexDurationType::SeveralSeconds)
                    return TimexDurationType::SeveralSeconds;
            }

            if(strlen($input) == 3)
            {
                if($input == TimexDurationType::SeveralWeeks)
                    return TimexDurationType::SeveralWeeks;

                if($input == TimexDurationType::SeveralYears)
                    return TimexDurationType::SeveralYears;

                if($input == TimexDurationType::SeveralMonths)
                    return TimexDurationType::SeveralMonths;

                if($input == TimexDurationType::SeveralDays)
                    return TimexDurationType::SeveralDays;
            }

            if(
                strlen($input) > 2 && // This checks out
                substr($input, 0, 3) !== "PTX" && // This checks out
                substr($input, 0, 1) == "P" // This checks out
            )
            {
                return TimexDurationType::Date;
            }

            throw new CannotDetermineDurationTypeException("The Timex Duration '$input' cannot be determined");
        }

        /**
         * Attempts to parse the Timex Time duration type
         *
         * Parses the time duration type
         *
         * @param string $input
         * @return string
         * @throws InvalidTimexTimeDurationException
         * @throws TimexDurationParseException
         */
        public static function parseTimexTimeDurationType(string $input): string
        {
            if(strlen($input) < 2)
                throw new InvalidTimexTimeDurationException("The given Timex3 Time duration format must be greater than 2 characters");

            if(strtoupper(substr($input, 0, 2)) !== "PT")
                throw new InvalidTimexTimeDurationException("The given input '$input' not a valid Timex Time duration");

            if(strtoupper(substr($input, 0, 4)) == "PT0.") // Milliseconds ends in S, so this check is required.
            {
                return TimexTimeDuration::Milliseconds;
            }
            else
            {
                switch(strtoupper(substr($input, strlen($input) -1, 1)))
                {
                    case TimexTimeDuration::Hour:
                        return TimexTimeDuration::Hour;

                    case TimexTimeDuration::Minute:
                        return TimexTimeDuration::Minute;

                    case TimexTimeDuration::Second:
                        return TimexTimeDuration::Second;
                }
            }

            throw new TimexDurationParseException("The given input '$input' cannot be parsed as a Timex3 Time duration format");
        }

        /**
         * Parses the Timex Date duration type
         *
         * @param string $input
         * @return string
         * @throws InvalidTimexDateDurationException
         * @throws TimexDurationParseException
         */
        public static function parseTimexDateDurationType(string $input): string
        {
            if(strlen($input) < 1)
                throw new InvalidTimexDateDurationException("The given Timex3 Date duration format must be greater than 1 character");

            if(strtoupper(substr($input, 0, 1)) !== "P")
                throw new InvalidTimexDateDurationException("The given input '$input' not a valid Timex Date duration");

            switch(strtoupper(substr($input, strlen($input) -1, 1)))
            {
                case TimexDateDuration::Year:
                    return TimexDateDuration::Year;

                case TimexDateDuration::Month:
                    return TimexDateDuration::Month;

                case TimexDateDuration::Day:
                    return TimexDateDuration::Day;

                case TimexDateDuration::Week:
                    return TimexDateDuration::Week;
            }

            throw new TimexDurationParseException("The given input '$input' cannot be parsed as a Timex3 Time duration format");
        }

        /**
         * Returns the Timex duration value
         *
         * @param string $input
         * @return int
         * @throws CannotDetermineDurationTypeException
         * @throws InvalidTimexDurationTypeException
         * @throws InvalidTimexTimeDurationException
         * @throws TimexDurationParseException
         */
        public static function parseTimexDurationValue(string $input): int
        {
            if(
                self::parseTimexDurationType($input) == TimexDurationType::Time  ||
                self::parseTimexDurationType($input) == TimexDurationType::Date
            )
            {
                // Haha very complic!!!1111!11
                if((bool)preg_match("/([0-9]+)/", $input, $matches) == true)
                    return (int)$matches[0];

                throw new TimexDurationParseException("Cannot extract the value from '$input'");
            }

            throw new InvalidTimexDurationTypeException("The given Timex duration type '" . self::parseTimexTimeDurationType($input) . "' from the input '$input' is not valid for this method, must be Time or Date");
        }

        /**
         * Makes the Timex Duration Time type more beautiful.
         *
         * @param string $input
         * @return string
         */
        public static function TimexDurationTimeToString(string $input): string
        {
            return match ($input) {
                TimexTimeDuration::Hour => DurationTimeTypes::Hour,
                TimexTimeDuration::Minute => DurationTimeTypes::Minute,
                TimexTimeDuration::Milliseconds => DurationTimeTypes::Milliseconds,
                TimexTimeDuration::Second => DurationTimeTypes::Second,
                default => DurationTimeTypes::Unknown,
            };
        }

        /**
         * Makes the Timex Duration Date type more beautiful.
         *
         * @param string $input
         * @return string
         */
        public static function TimexDurationDateToString(string $input): string
        {
            return match ($input) {
                TimexDateDuration::Year => DurationDateTypes::Year,
                TimexDateDuration::Month => DurationDateTypes::Month,
                TimexDateDuration::Day => DurationDateTypes::Day,
                TimexDateDuration::Week => DurationDateTypes::Week,
                default => DurationDateTypes::Unknown,
            };
        }

        public static function TimexDurationTypeToString(string $input): string
        {
            return match ($input) {
                TimexDurationType::SeveralMilliseconds => DurationType::SeveralMilliseconds,
                TimexDurationType::SeveralHours => DurationType::SeveralHours,
                TimexDurationType::SeveralMinutes => DurationType::SeveralMinutes,
                TimexDurationType::SeveralSeconds => DurationType::SeveralSeconds,
                TimexDurationType::SeveralWeeks => DurationType::SeveralWeeks,
                TimexDurationType::SeveralYears => DurationType::SeveralYears,
                TimexDurationType::SeveralMonths => DurationType::SeveralMonths,
                TimexDurationType::SeveralDays => DurationType::SeveralDays,
                TimexDurationType::Time => DurationType::Time,
                TimexDurationType::Date => DurationType::Date,
                default => DurationDateTypes::Unknown,
            };
        }
    }