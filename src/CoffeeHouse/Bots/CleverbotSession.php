<?php


    namespace CoffeeHouse\Bots;

    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Objects\BotThought;


    /**
     * Class CleverbotSession
     * @package CoffeeHouse\Bots
     */
    class CleverbotSession
    {

        /**
         * @var Cleverbot
         */
        private $bot;

        /**
         * @var array
         */
        private $headers;

        /**
         * @var array
         */
        private $cookies;

        /**
         * @var array
         */
        private $vars;

        /**
         * @var string
         */
        private $language;

        /**
         * CleverbotSession constructor.
         * @param Cleverbot $bot
         * @param string $language
         * @param array|null $session
         * @throws BotSessionException
         */
        public function __construct(Cleverbot $bot, string $language, $session = null)
        {
            $this->bot = $bot;
            $this->headers = array();
            $this->vars = array();
            $this->cookies = array();
            $this->language = $language;

            if($session == null)
            {
                $this->setHeaders();
                $this->setVariables();
                $this->setCookies();
            }
            else
            {
                $this->headers = $session['headers'];
                $this->cookies = $session['cookies'];
                $this->vars = $session['vars'];
                $this->language = $session['language'];
            }

        }

        /**
         * Sets the required headers
         */
        private function setHeaders()
        {
            $this->headers['Accept-Language'] = $this->language . ';q=1.0';
        }

        /**
         * Sets the variables
         */
        private function setVariables()
        {
            //$this->vars['start'] = 'y';
            $this->vars['stimulus'] = '';
            $this->vars['cb_settings_language'] = $this->language;
            $this->vars['cb_settings_scripting'] = 'no';
            $this->vars['islearning'] = '1';
            $this->vars['icognoid'] = 'wsf';
            //$this->vars['fno'] = '0';
            //$this->vars['sub'] = 'Say';
            //$this->vars['cleanslate'] = 'false';
        }

        /**
         * Sets the cookies variables
         * @throws BotSessionException
         */
        private function setCookies()
        {
            // Supposed to update $cookies with new variables?
            Utilities::request(
                $this->bot->getBaseUrl(),
                $this->cookies,
                null,
                $this->headers
            );
        }

        /**
         * Processes a thought and returns the results
         *
         * @param string $thought
         * @return BotThought
         * @throws BotSessionException
         */
        public function thinkThought(string $thought): BotThought
        {
            $this->vars['stimulus'] = $thought;

            // Debug this (Creates icognoid value)
            $data = http_build_query($this->vars);
            $this->vars['icognocheck'] = Hashing::icognocheckCode($data);

            $Response = Utilities::request(
                $this->bot->getServiceUrl(),
                $this->cookies,
                $this->vars,
                $this->headers
            );
            $ResponseValues = explode("\r", $Response);

            // Parses the values
            $this->vars['sessionid'] = Utilities::stringAtIndex($ResponseValues, 1);
            $this->vars['logurl'] = Utilities::stringAtIndex($ResponseValues, 2);
            $this->vars['vText8'] = Utilities::stringAtIndex($ResponseValues, 3);
            $this->vars['vText7'] = Utilities::stringAtIndex($ResponseValues, 4);
            $this->vars['vText6'] = Utilities::stringAtIndex($ResponseValues, 5);
            $this->vars['vText5'] = Utilities::stringAtIndex($ResponseValues, 6);
            $this->vars['vText4'] = Utilities::stringAtIndex($ResponseValues, 7);
            $this->vars['vText3'] = Utilities::stringAtIndex($ResponseValues, 8);
            $this->vars['vText2'] = Utilities::stringAtIndex($ResponseValues, 9);
            $this->vars['prevref'] = Utilities::stringAtIndex($ResponseValues, 10);

            $Text = Utilities::stringAtIndex($ResponseValues, 0);

            if(!is_null($Text))
            {
                $Text = preg_replace_callback(
                    '/\|([01234567890ABCDEF]{4})/',
                    function($matches)
                    {
                        return iconv(
                            'UCS-4LE', 'UTF-8',
                            pack('V', hexdec($matches[0]))
                        );
                    }, $Text);
            }
            else
            {
                $Text = 'COFFEE_HOUSE ERROR';
            }

            return new BotThought($thought, $Text, $this->exportSession());
        }

        /**
         * Exports the current session which can be used later on
         *
         * @return array
         */
        public function exportSession(): array
        {
            return array(
                'headers' => $this->headers,
                'cookies' => $this->cookies,
                'vars' => $this->vars,
                'language' => $this->language
            );
        }
    }