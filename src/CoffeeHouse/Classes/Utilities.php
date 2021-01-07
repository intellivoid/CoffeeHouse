<?php


    namespace CoffeeHouse\Classes;


    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Objects\HttpResponse;
    
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
         * @return mixed|string
         */
        public static function stringAtIndex($strings, $index)
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
            switch(strtolower($input))
            {
                /**
                 * also known as Abkhaz
                 */
                case "аҧсуа бызшәа":
                case "аҧсшәа":
                case "abkhazian":
                case "abk":
                case "ab":
                    return "ab";

                case "afaraf":
                case "afar":
                case "aar":
                case "aa":
                    return "aa";

                case "afrikaans":
                case "afr":
                case "af":
                    return "af";

                /**
                 * macrolanguage, Twi is [tw/twi], Fanti is [fat]
                 */
                case "akan":
                case "aka":
                case "ak":
                    return "ak";

                /**
                 * macrolanguage, "Albanian Phylozone" in 639-6
                 */
                case "albanian":
                case "shqip":
                case "alb":
                case "sqi":
                case "sq":
                    return "sq";

                case "amharic":
                case "አማርኛ":
                case "amh":
                case "am":
                    return "am";

                /**
                 * macrolanguage, Standard Arabic is [arb]
                 */
                case "arabic":
                case "العربية":
                case "ara":
                case "ar":
                    return "ar";

                case "aragonese":
                case "aragonés":
                case "arg":
                case "an":
                    return "an";

                /**
                 * also known as Հայերէն; ISO 639-3 code "hye" is for Eastern Armenian, "hyw" is for Western Armenian,
                 * and "xcl" is for Classical Armenian
                 */
                case "armenian":
                case "Հայերեն":
                case "arm":
                case "hye":
                case "hy":
                    return "hy";

                case "assamese":
                case "অসমীয়া":
                case "asm":
                case "as":
                    return "as";

                /**
                 * also known as Avar
                 */
                case "avaric":
                case "авар мацӀ":
                case "магӀарул мацӀ":
                case "avar":
                case "ava":
                case "av":
                    return "av";

                /**
                 * ancient
                 */
                case "avestan":
                case "avesta":
                case "ave":
                case "ae":
                    return "ae";

                /**
                 * macrolanguage
                 */
                case "aymara":
                case "aymar aru":
                case "aym":
                case "ay":
                    return "ay";

                /**
                 * macrolanguage
                 */
                case "azerbaijani":
                case "azərbaycan dili":
                case "تۆرکجه":
                case "aze":
                case "az":
                    return "az";

                case "bamanankan":
                case "bambara":
                case "bam":
                case "bm":
                    return "bm";

                case "bashkir":
                case "башҡорт теле":
                case "bak":
                case "ba":
                    return "ba";

                case "basque":
                case "euskara":
                case "euskera":
                case "baq":
                case "eus":
                case "eu":
                    return "eu";

                case "belarusian":
                case "беларуская мова":
                case "bel":
                case "be":
                    return "be";

                /**
                 * also known as Bangla
                 */
                case "bengali":
                case "bangla":
                case "বাংলা":
                case "ben":
                case "bn":
                    return "bn";

                /**
                 * collective language code for Bhojpuri, Magahi, and Maithili
                 */
                case "bihari languages":
                case "bihari":
                case "भोजपुरी":
                case "bih":
                case "bh":
                    return "bh";

                /**
                 * Language formed from English and Ni-Vanuatu, with some French influence.
                 */
                case "bislama":
                case "bis":
                case "bi":
                    return "bi";

                case "bosnian":
                case "bosanski jezik":
                case "bos":
                case "bs":
                    return "bs";

                case "breton":
                case "bre":
                case "br":
                    return "br";

                case "bulgarian":
                case "български език":
                case "bul":
                case "bg":
                    return "bg";

                /**
                 * also known as Myanmar
                 */
                case "burmese":
                case "ဗမာစာ":
                case "myanmar":
                case "bur":
                case "mya":
                case "my":
                    return "my";

                case "catalan":
                case "valencian":
                case "català":
                case "valencià":
                case "cat":
                case "ca":
                    return "ca";

                case "chamorro":
                case "chamoru":
                case "cha":
                case "ch":
                    return "ch";

                case "chechen":
                case "нохчийн мотт":
                case "che":
                case "ce":
                    return "ce";

                case "chichewa":
                case "chewa":
                case "nyanja":
                case "chicheŵa":
                case "chinyanja":
                case "nya":
                case "ny":
                    return "ny";

                /**
                 * macrolanguage
                 */
                case "chinese":
                case "中文":
                case "zhōngwén":
                case "汉语":
                case "漢語":
                case "chi":
                case "zho":
                case "zh":
                    return "zh";

                case "chuvash":
                case "чӑваш чӗлхи":
                case "chv":
                case "cv":
                    return "cv";

                case "cornish":
                case "kernewek":
                case "cor":
                case "kw":
                    return "kw";

                case "corsican":
                case "corsu":
                case "lingua corsa":
                case "cos":
                case "co":
                    return "co";

                /**
                 * macrolanguage
                 */
                case "cree":
                case "ᓀᐦᐃᔭᐍᐏᐣ":
                case "cre":
                case "cr":
                    return "cr";

                case "croatian":
                case "hrvatski jezik":
                case "hrv":
                case "hr":
                    return "hr";

                case "czech":
                case "čeština":
                case "český jazyk":
                case "ces":
                case "cze":
                case "cs":
                    return "cs";

                case "danish":
                case "dansk":
                case "dan":
                case "da":
                    return "da";

                case "divehi":
                case "dhivehi":
                case "maldivian":
                case "ދިވެހި":
                case "div":
                case "dv":
                    return "dv";

                /**
                 *  Flemish is not to be confused with the closely related West Flemish which is referred to as Vlaams
                 * (Dutch for "Flemish") in ISO 639-3 and has the ISO 639-3 code vls
                 */
                case "dutch":
                case "flemish":
                case "nederlands":
                case "vlaams":
                case "nld":
                case "dut":
                case "nl":
                    return "nl";

                case "dzongkha":
                case "རྫོང་ཁ":
                case "dzo":
                case "dz":
                    return "dz";

                case "english":
                case "eng":
                case "en":
                    return "en";

                /**
                 * 	constructed, initiated from L.L. Zamenhof, 1887
                 */
                case "esperanto":
                case "epo":
                case "eo":
                    return "eo";

                /**
                 * macrolanguage
                 */
                case "estonian":
                case "eesti":
                case "eesti keel":
                case "est":
                case "et":
                    return "et";

                case "eʋegbe":
                case "ewe":
                case "ee":
                    return "ee";

                case "faroese":
                case "føroyskt":
                case "fao":
                case "fo":
                    return "fo";

                case "fijian":
                case "vosa vakaviti":
                case "fij":
                case "fj":
                    return "fj";

                case "finnish":
                case "suomi":
                case "suomen kieli":
                case "fin":
                case "fi":
                    return "fi";

                case "french":
                case "français":
                case "langue française":
                case "fra":
                case "fre":
                case "fr":
                    return "fr";

                /**
                 *  macrolanguage, also known as Fula
                 */
                case "fulah":
                case "fulfulde":
                case "pulaar":
                case "pular":
                case "fula":
                case "ful":
                case "ff":
                    return "ff";

                case "galician":
                case "galego":
                case "glg":
                case "gl":
                    return "gl";

                case "georgian":
                case "ქართული":
                case "kat":
                case "geo":
                case "ka":
                    return "ka";

                case "german":
                case "deutsch":
                case "deu":
                case "ger":
                case "de":
                    return "de";

                case "greek":
                case "ελληνικά":
                case "ell":
                case "gre":
                case "el":
                    return "el";

                /**
                 * macrolanguage
                 */
                case "guarani":
                case "avañe'ẽ":
                case "grn":
                case "gn":
                    return "gn";

                case "Gujarati":
                case "ગુજરાતી":
                case "guj":
                case "gu":
                    return "gu";

                case "haitian":
                case "haitian creole":
                case "kreyòl ayisyen":
                case "hat":
                case "ht":
                    return "ht";

                case "hausa":
                case "هَوُسَ":
                case "hau":
                case "ha":
                    return "ha";

                /**
                 * Modern Hebrew. Code changed in 1989 from original ISO 639:1988, iw.
                 */
                case "hebrew":
                case "עברית":
                case "heb":
                case "he":
                    return "he";

                case "herero":
                case "her":
                case "hz":
                    return "hz";

                case "hindi":
                case "हिन्दी":
                case "हिंदी":
                case "hin":
                case "hi":
                    return "hi";

                case "hiri motu":
                case "hmo":
                case "ho":
                    return "ho";

                case "hungarian":
                case "magyar":
                case "hun":
                case "hu":
                    return "hu";

                /**
                 * constructed by International Auxiliary Language Association
                 */
                case "interlingua":
                case "ina":
                case "ia":
                    return "ia";

                /**
                 * Covered by macrolanguage [ms/msa]. Changed in 1989 from original ISO 639:1988, in.
                 */
                case "indonesian":
                case "bahasa indonesia":
                case "ind":
                case "id":
                    return "id";

                /**
                 * constructed by Edgar de Wahl, first published in 1922
                 */
                case "interlingue":
                case "occidental":
                case "ile":
                case "ie":
                    return "ie";

                case "irish":
                case "gaeilge":
                case "gle":
                case "ga":
                    return "ga";

                case "igbo":
                case "asụsụ igbo":
                case "ibo":
                case "ig":
                    return "ig";

                /**
                 * macrolanguage
                 */
                case "inupiaq":
                case "iñupiaq":
                case "iñupiatun":
                case "ipk":
                case "ik":
                    return "ik";

                /**
                 * constructed by De Beaufront, 1907, as variation of Esperanto
                 */
                case "ido":
                case "io":
                    return "io";

                case "icelandic":
                case "Íslenska":
                case "ice":
                case "isl":
                case "is":
                    return "is";

                case "italian":
                case "italiano":
                case "ita":
                case "it":
                    return "it";

                /**
                 * macrolanguage
                 */
                case "inuktitut":
                case "ᐃᓄᒃᑎᑐᑦ":
                case "iku":
                case "iu":
                    return "iu";

                case "japanese":
                case "日本語":
                case "にほんご":
                case "jpn":
                case "ja":
                    return "ja";

                case "javanese":
                case "ꦧꦱꦗꦮ":
                case "basa jawa":
                case "jav":
                case "jv":
                    return "jv";

                case "kalaallisut":
                case "greenlandic":
                case "kalaallit oqaasii":
                case "kal":
                case "kl":
                    return "kl";

                case "kannada":
                case "ಕನ್ನಡ":
                case "kan":
                case "kn":
                    return "kn";

                /**
                 * macrolanguage
                 */
                case "kanuri":
                case "kau":
                case "kr":
                    return "kr";

                case "kashmiri":
                case "कश्मीरी":
                case "كشميري":
                case "kas":
                case "ks":
                    return "ks";

                case "kazakh":
                case "қазақ тілі":
                case "kaz":
                case "kk":
                    return "kk";

                /**
                 * also known as Khmer or Cambodian
                 */
                case "central khmer":
                case "cambodian":
                case "khmer":
                case "ខ្មែរ":
                case "ខេមរភាសា":
                case "ភាសាខ្មែរ":
                case "khm":
                case "km":
                    return "km";

                case "kinyarwanda":
                case "ikinyarwanda":
                case "kin":
                case "rw":
                    return "rw";

                case "kirghiz":
                case "kyrgyz":
                case "Кыргызча":
                case "Кыргыз тили":
                case "kir":
                case "ky":
                    return "ky";

                /**
                 * macrolanguage
                 */
                case "komi":
                case "коми кыв":
                case "kom":
                case "kv":
                    return "kv";

                /**
                 * macrolanguage
                 */
                case "kongo":
                case "kon":
                case "kg":
                    return "kg";

                case "korean":
                case "한국어":
                case "kor":
                case "ko":
                    return "ko";

                /**
                 * macrolanguage
                 */
                case "kurdish":
                case "kurdî":
                case "کوردی":
                case "kur":
                case "ku":
                    return "ku";

                case "Kuanyama":
                case "Kwanyama":
                case "kua":
                case "kj":
                    return "kj";

                /**
                 * ancient
                 */
                case "katin":
                case "latine":
                case "latina":
                case "lat":
                case "la":
                    return "la";

                case "luxembourgish":
                case "letzeburgesch":
                case "lëtzebuergesch":
                case "ltz":
                case "lb":
                    return "lb";

                case "ganda":
                case "luganda":
                case "lug":
                case "lg":
                    return "lg";

                case "limburgan":
                case "limburger":
                case "limburgish":
                case "limburgs":
                case "lim":
                case "li":
                    return "li";

                case "lingala":
                case "lingála":
                case "lin":
                case "ln":
                    return "ln";

                case "lao":
                case "ພາສາລາວ":
                case "lo":
                    return "lo";

                case "lithuanian":
                case "lietuvių kalba":
                case "lit":
                case "lt":
                    return "lt";

                /**
                 * also known as Luba-Shaba
                 */
                case "luba-shaba":
                case "luba-katanga":
                case "kiluba":
                case "lub":
                case "lu":
                    return "lu";
                /**
                 * latviešu valoda
                 */
                case "Latvian":
                case "latviešu valoda":
                case "lav":
                case "lv":
                    return "lv";

                case "manx":
                case "gaelg":
                case "gailck":
                case "glv":
                case "gv":
                    return "gv";

                case "Macedonian":
                case "македонски јазик":
                case "mkd":
                case "mac":
                case "mk":
                    return "mk";

                /**
                 * macrolanguage, Standard Malay is [zsm], Indonesian is [id/ind]
                 */
                case "Malagasy":
                case "fiteny malagasy":
                case "mlg":
                case "mg":
                    return "mg";

                /**
                 * macrolanguage, Standard Malay is [zsm], Indonesian is [id/ind]
                 */
                case "malay":
                case "bahasa melayu":
                case "بهاس ملايو":
                case "msa":
                case "may":
                case "ms":
                    return "ms";

                case "malayalam":
                case "മലയാളം":
                case "mal":
                case "ml":
                    return "ml";

                case "maltese":
                case "malti":
                case "mlt":
                case "mt":
                    return "mt";

                /**
                 * also known as Māori
                 */
                case "maori":
                case "māori":
                case "te reo māori":
                case "mri":
                case "mao":
                case "mi":
                    return "mi";

                /**
                 * also known as Marāṭhī
                 */
                case "marāṭhī":
                case "marathi":
                case "मराठी":
                case "mar":
                case "mr":
                    return "mr";

                case "marshallese":
                case "kajin m̧ajeļ":
                case "mah":
                case "mh":
                    return "mh";

                /**
                 * macrolanguage
                 */
                case "mongolian":
                case "mонгол хэл":
                case "mon":
                case "mn":
                    return "mn";

                /**
                 * also known as Nauruan
                 */
                case "nauru":
                case "nauruan":
                case "dorerin naoero":
                case "nau":
                case "na":
                    return "na";

                case "navajo":
                case "navaho":
                case "diné bizaad":
                case "nav":
                case "nv":
                    return "nv";

                /**
                 * also known as Northern Ndebele
                 */
                case "north ndebele":
                case "isindebele":
                case "ndebele":
                case "nde":
                case "nd":
                    return "nd";

                /**
                 * macrolanguage
                 */
                case "nepali":
                case "नेपाली":
                case "nep":
                case "ne":
                    return "ne";

                case "ndonga":
                case "owambo":
                case "ndo":
                case "ng":
                    return "ng";

                /**
                 * Covered by macrolanguage [no/nor]
                 */
                case "norwegian bokmål":
                case "norsk bokmål":
                case "nob":
                case "nb":
                    return "nb";

                /**
                 * Covered by macrolanguage [no/nor]
                 */
                case "norwegian nynorsk":
                case "norsk nynorsk":
                case "nno":
                case "nn":
                    return "nn";

                /**
                 * macrolanguage, Bokmål is [nb/nob], Nynorsk is [nn/nno]
                 */
                case "norwegian":
                case "nor":
                case "no":
                    return "no";

                /**
                 * Standard form of Yi languages
                 */
                case "sichuan yi":
                case "nuosu":
                case "nuosuhxop":
                case "ꆈꌠ꒿":
                case "ꆈꌠ꒿ nuosuhxop":
                case "iii":
                case "ii":
                    return "ii";

                /**
                 * also known as Southern Ndebele
                 */
                case "south ndebele":
                case "nbl":
                case "nr":
                    return "nr";

                case "occitan":
                case "lenga d'òc":
                case "oci":
                case "oc":
                    return "oc";

                /**
                 * macrolanguage, also known as Ojibwe
                 */
                case "Ojibwa":
                case "ᐊᓂᔑᓈᐯᒧᐎᓐ":
                case "oji":
                case "oj":
                    return "oj";

                /**
                 * ancient, in use by Orthodox Church
                 */
                case "church slavic":
                case "old slavonic":
                case "church slavonic":
                case "old bulgarian":
                case "old church slavonic":
                case "ѩзыкъ словѣньскъ":
                case "chu":
                case "cu":
                    return "cu";

                /**
                 * macrolanguage
                 */
                case "oromo":
                case "afaan oromoo":
                case "orm":
                case "om":
                    return "om";

                /**
                 * macrolanguage, also known as Odia
                 */
                case "oriya":
                case "odia":
                case "ଓଡ଼ିଆ":
                case "ori":
                case "or":
                    return "or";

                case "ossetian":
                case "ossetic":
                case "ирон æвзаг":
                case "oss":
                case "os":
                    return "os";

                case "punjabi":
                case "panjabi":
                case "ਪੰਜਾਬੀ":
                case "پنجابی":
                case "pan":
                case "pa":
                    return "pa";

                /**
                 * ancient, also known as Pāli
                 */
                case "pali":
                case "pāli":
                case "पालि":
                case "पाळि":
                case "pli":
                case "pi":
                    return "pi";

                /**
                 * macrolanguage, also known as Farsi
                 */
                case "persian":
                case "farsi":
                case "فارسی":
                case "per":
                case "far":
                case "fa":
                    return "fa";

                case "polish":
                case "język polski":
                case "polszczyzna":
                case "pol":
                case "pl":
                    return "pl";

                /**
                 * macrolanguage
                 */
                case "pashto":
                case "pushto":
                case "پښتو":
                case "pus":
                case "ps":
                    return "ps";

                case "portuguese":
                case "português":
                case "por":
                case "pt":
                    return "pt";

                /**
                 * macrolanguage
                 */
                case "quechua":
                case "runa simi":
                case "kichwa":
                case "que":
                case "qu":
                    return "qu";

                case "romansh":
                case "rumantsch grischun":
                case "roh":
                case "rm":
                    return "rm";

                /**
                 * also known as Kirundi
                 */
                case "rundi":
                case "kirundi":
                case "ikirundi":
                case "run":
                case "rn":
                    return "rn";

                /**
                 * The identifiers mo and mol are deprecated, leaving ro and ron (639-2/T) and rum (639-2/B) the current
                 * language identifiers to be used for the variant of the Romanian language also known as Moldavian and
                 * Moldovan in English and moldave in French. The identifiers mo and mol will not be assigned to
                 * different items, and recordings using these identifiers will not be invalid.
                 */
                case "romanian":
                case "moldavian":
                case "moldovan":
                case "română":
                case "ron":
                case "rum":
                case "ro":
                    return "ro";

                case "russian":
                case "русский":
                case "rus":
                case "ru":
                    return "ru";

                /**
                 * ancient, still spoken, also known as Saṃskṛta
                 */
                case "sanskrit":
                case "saṃskṛta":
                case "संस्कृतम्":
                case "san":
                case "sa":
                    return "sa";

                /**
                 * macrolanguage
                 */
                case "sardinian":
                case "sardu":
                case "srd":
                case "sc":
                    return "sc";

                case "sindhi":
                case "सिन्धी":
                case "سنڌي، سندھی":
                case "snd":
                case "sd":
                    return "sd";

                case "northern sami":
                case "davvisámegiella":
                case "sme":
                case "se":
                    return "se";

                case "samoan":
                case "gagana fa'a samoa":
                case "smo":
                case "sm":
                    return "sm";

                case "sango":
                case "yângâ tî sängö":
                case "sag":
                case "sg":
                    return "sg";

                /**
                 * The ISO 639-2/T code srp deprecated the ISO 639-2/B code scc
                 */
                case "serbian":
                case "српски језик":
                case "srp":
                case "sr":
                    return "sr";

                case "gaelic":
                case "scottish gaelic":
                case "gàidhlig":
                case "gla":
                case "gd":
                    return "gd";

                case "shona":
                case "chishona":
                case "sna":
                case "sn":
                    return "sn";

                case "sinhala":
                case "sinhalese":
                case "සිංහල":
                case "sin":
                case "si":
                    return "si";

                case "slovak":
                case "slovenčina":
                case "slovenský jazyk":
                case "slk":
                case "slo":
                case "sk":
                    return "sk";

                case "slovenian":
                case "slovene":
                case "slovenski jezik":
                case "slovenščina":
                case "slv":
                case "sl":
                    return "sl";

                case "somali":
                case "soomaaliga":
                case "af soomaali":
                case "som":
                case "so":
                    return "so";

                case "southern sotho":
                case "sesotho":
                case "sot":
                case "st":
                    return "st";

                case "spanish":
                case "castilian":
                case "español":
                case "spa":
                case "es":
                    return "es";

                case "sundanese":
                case "basa sunda":
                case "sun":
                case "su":
                    return "su";

                /**
                 * macrolanguage
                 */
                case "swahili":
                case "swa":
                case "sw":
                    return "sw";

                /**
                 * also known as Swazi
                 */
                case "swazi":
                case "swati":
                case "siswati":
                case "ssw":
                case "ss":
                    return "ss";

                case "swedish":
                case "svenska":
                case "swe":
                case "sv":
                    return "sv";

                case "tamil":
                case "தமிழ்":
                case "tam":
                case "ta":
                    return "ta";

                case "telugu":
                case "తెలుగు":
                case "tel":
                case "te":
                    return "te";

                case "tajik":
                case "тоҷикӣ":
                case "toçikī":
                case "تاجیکی":
                case "tgk":
                case "tg":
                    return "tg";

                case "thai":
                case "ไทย":
                case "tha":
                case "th":
                    return "th";

                case "tigrinya":
                case "ትግርኛ":
                case "tir":
                case "ti":
                    return "ti";

                /**
                 * also known as Standard Tibetan
                 */
                case "standard tibetan":
                case "tibetan":
                case "ོད་ཡིག":
                case "bod":
                case "tib":
                case "bo":
                    return "bo";

                case "turkmen":
                case "türkmen":
                case "tүркмен":
                case "tuk":
                case "tk":
                    return "tk";

                /**
                 * Note: Filipino (Pilipino) has the code [fil]
                 */
                case "tagalog":
                case "wikang tagalog":
                case "tgl":
                case "tl":
                    return "tl";

                case "tswana":
                case "setswana":
                case "tsn":
                case "tn":
                    return "tn";

                case "tongan":
                case "tonga":
                case "faka tonga":
                case "ton":
                case "to":
                    return "to";

                case "turkish":
                case "türkçe":
                case "tur":
                case "tr":
                    return "tr";

                case "tsonga":
                case "xitsonga":
                case "tso":
                case "ts":
                    return "ts";

                case "tatar":
                case "татар теле":
                case "tatar tele":
                case "tat":
                case "tt":
                    return "tt";

                /**
                 * Covered by macrolanguage [ak/aka]
                 */
                case "twi":
                case "tw":
                    return "tw";

                /**
                 * One of the Reo Mā`ohi (languages of French Polynesia)
                 */
                case "tahitian":
                case "reo tahiti":
                case "tah":
                case "ty":
                    return "ty";

                case "uighur":
                case "uyghur":
                case "ئۇيغۇرچە":
                case "uyghurche":
                case "uig":
                case "ug":
                    return "ug";

                case "ukrainian":
                case "Українська":
                case "ukr":
                case "uk":
                    return "uk";

                case "urdu":
                case "اردو":
                case "urd":
                case "ur":
                    return "ur";

                /**
                 * macrolanguage
                 */
                case "uzbek":
                case "oʻzbek":
                case "Ўзбек":
                case "أۇزبېك":
                case "uzb":
                case "uz":
                    return "uz";

                case "venda":
                case "tshivenḓa":
                case "ven":
                case "ve":
                    return "ve";

                case "vietnamese":
                case "tiếng việt":
                case "vie":
                case "vi":
                    return "vi";

                /**
                 * constructed
                 */
                case "volapük":
                case "volapuk":
                case "vol":
                case "vo":
                    return "vo";

                case "walloon":
                case "walon":
                case "wln":
                case "wa":
                    return "wa";

                case "welsh":
                case "cymraeg":
                case "cym":
                case "wel":
                case "cy":
                    return "cy";

                case "wolof":
                case "wollof":
                case "wol":
                case "wo":
                    return "wo";

                case "western frisian":
                case "frisian":
                case "frysk":
                case "fry":
                case "fy":
                    return "fy";

                case "xhosa":
                case "isixhosa":
                case "xho":
                case "xh":
                    return "xh";

                /**
                 * macrolanguage. Changed in 1989 from original ISO 639:1988, ji.
                 */
                case "yiddish":
                case "ייִדיש":
                case "yid":
                case "yi":
                    return "yi";

                case "yoruba":
                case "yorùbá":
                case "yor":
                case "yo":
                    return "yo";

                /**
                 * macrolanguage
                 */
                case "zhuang":
                case "chuang":
                case "saɯ cueŋƅ":
                case "saw cuengh":
                case "zha":
                case "za":
                    return "za";

                case "zulu":
                case "isizulu":
                case "zul":
                case "zu":
                    return "zu";

                default:
                    throw new InvalidLanguageException("The given language '$input' is not supported");
            }
        }
    }