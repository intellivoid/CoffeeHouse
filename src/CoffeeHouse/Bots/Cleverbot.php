<?php


    namespace CoffeeHouse\Bots;

    use CoffeeHouse\Abstracts\ForeignSessionSearchMethod;
    use CoffeeHouse\Classes\Hashing;
    use CoffeeHouse\Classes\Utilities;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\BotSessionException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\ForeignSessionNotFoundException;
    use CoffeeHouse\Exceptions\InvalidMessageException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\LocalSessionNotFoundException;
    use CoffeeHouse\Exceptions\NoResultsFoundException;
    use CoffeeHouse\Objects\BotThought;
    use CoffeeHouse\Objects\ForeignSession;
    use CoffeeHouse\Objects\LocalSession;
    use Exception;

    /**
     * Class Cleverbot
     * @package CoffeeHouse\Bots
     */
    class Cleverbot
    {

        /**
         * @var string
         */
        private $baseUrl;

        /**
         * @var string
         */
        private $serviceUrl;

        /**
         * @var CoffeeHouse
         */
        private $coffeeHouse;

        /**
         * @var ForeignSession|null
         */
        private $Session;

        /**
         * @var LocalSession|null
         */
        private $LocalSession;

        /**
         * Cleverbot constructor.
         * @param CoffeeHouse $coffeeHouse
         * @throws Exception
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->baseUrl = 'http://cleverbot.com';
            $this->serviceUrl = 'https://www.cleverbot.com/webservicemin?uc=UseOfficialCleverbotAPI';
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * @param string $language
         * @throws BotSessionException
         * @throws DatabaseException
         * @throws ForeignSessionNotFoundException
         * @throws InvalidSearchMethodException
         * @throws LocalSessionNotFoundException
         * @throws NoResultsFoundException
         */
        public function newSession($language="en")
        {
            $this->Session = $this->coffeeHouse->getForeignSessionsManager()->createSession($language);

            $this->Session->Variables = array(
                'stimulus'              => '',
                'cb_settings_language'  => $language,
                'cb_settings_scripting' => 'no',
                'islearning'            => '1',
                'icognoid'              => 'wsf'
            );

            $this->Session->Headers = array(
                'Accept-Language'       => $language . ';q=1.0'
            );

            $this->Session->Cookies = array();

            // Get the initial cookies
            $Response = Utilities::request(
                $this->baseUrl,
                $this->Session->Cookies,
                null,
                $this->Session->Headers
            );

            $this->Session->Cookies = $Response->cookies;
            $this->Session->Language = $language;

            $this->coffeeHouse->getForeignSessionsManager()->updateSession($this->Session);
            $this->LocalSession = $this->coffeeHouse->getLocalSessionManager()->createLocalSession($this->Session);

            $this->coffeeHouse->getDeepAnalytics()->tally('coffeehouse', 'lydia_sessions', 0);
        }

        /**
         * Loads an existing session
         *
         * @param string $session_id
         * @throws DatabaseException
         * @throws ForeignSessionNotFoundException
         * @throws InvalidSearchMethodException
         * @throws LocalSessionNotFoundException
         * @throws NoResultsFoundException
         * @noinspection PhpUnused
         */
        public function loadSession(string $session_id)
        {
            $this->Session = $this->coffeeHouse->getForeignSessionsManager()->getSession(
                ForeignSessionSearchMethod::bySessionId, $session_id
            );

            $this->LocalSession = $this->coffeeHouse->getLocalSessionManager()->createLocalSession($this->Session);
        }

        /**
         * @param string $input
         * @param bool $use_local_session
         * @return BotThought
         * @throws BotSessionException
         * @throws DatabaseException
         * @throws InvalidSearchMethodException
         * @throws NoResultsFoundException
         * @noinspection RegExpDuplicateCharacterInClass
         */
        public function think(string $input, bool $use_local_session=True): string
        {
            $this->Session->Variables['stimulus'] = $input;

            // Debug this (Creates icognoid value)
            $data = http_build_query($this->Session->Variables);
            $this->Session->Variables['icognocheck'] = Hashing::icognocheckCode($data);

            $Response = Utilities::request(
                $this->serviceUrl,
                $this->Session->Cookies,
                $this->Session->Variables,
                $this->Session->Headers
            );
            $ResponseValues = explode("\r", $Response->response);

            // Parses the values
            $this->Session->Variables['sessionid'] = Utilities::stringAtIndex($ResponseValues, 1);
            $this->Session->Variables['logurl'] = Utilities::stringAtIndex($ResponseValues, 2);
            $this->Session->Variables['vText8'] = Utilities::stringAtIndex($ResponseValues, 3);
            $this->Session->Variables['vText7'] = Utilities::stringAtIndex($ResponseValues, 4);
            $this->Session->Variables['vText6'] = Utilities::stringAtIndex($ResponseValues, 5);
            $this->Session->Variables['vText5'] = Utilities::stringAtIndex($ResponseValues, 6);
            $this->Session->Variables['vText4'] = Utilities::stringAtIndex($ResponseValues, 7);
            $this->Session->Variables['vText3'] = Utilities::stringAtIndex($ResponseValues, 8);
            $this->Session->Variables['vText2'] = Utilities::stringAtIndex($ResponseValues, 9);
            $this->Session->Variables['prevref'] = Utilities::stringAtIndex($ResponseValues, 10);

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

                $Text = Utilities::replaceThirdPartyMessages($Text);
            }
            else
            {
                $Text = 'COFFEE_HOUSE ERROR';
            }

            // Local session manager
            if($use_local_session)
            {
                $this->LocalSession->initialize($this->coffeeHouse);

                // Process the language
                $language = $this->Session->Language;

                try
                {
                    $this->LocalSession->LanguageLargeGeneralization = $this->coffeeHouse->getLanguagePrediction()->generalize(
                        $this->LocalSession->LanguageLargeGeneralization, $this->coffeeHouse->getLanguagePrediction()->predict($Text)
                    );

                    $this->LocalSession->PredictedLanguage = $this->LocalSession->LanguageLargeGeneralization->TopLabel;
                }
                catch(Exception $e)
                {
                    unset($e);
                }

                try
                {
                    $this->LocalSession->LanguageLargeGeneralization = $this->coffeeHouse->getLanguagePrediction()->generalize(
                        $this->LocalSession->LanguageLargeGeneralization, $this->coffeeHouse->getLanguagePrediction()->predict($input)
                    );

                    $this->LocalSession->PredictedLanguage = $this->LocalSession->LanguageLargeGeneralization->TopLabel;
                }
                catch(Exception $e)
                {
                    unset($e);
                }


                if($this->LocalSession->PredictedLanguage !== null)
                {
                    $language = $this->Session->Language;
                    $this->Session->Language = $this->LocalSession->PredictedLanguage;
                }

                try
                {
                    $this->LocalSession->EmotionLargeGeneralization = $this->coffeeHouse->getEmotionPrediction()->generalize(
                        $this->LocalSession->EmotionLargeGeneralization, $this->coffeeHouse->getEmotionPrediction()->predict($Text, $language)
                    );

                    $this->LocalSession->AiCurrentEmotion = $this->LocalSession->EmotionLargeGeneralization->TopLabel;
                }
                catch(Exception $e)
                {
                    unset($e);
                }

                $this->coffeeHouse->getLocalSessionManager()->updateLocalSession($this->LocalSession);
            }

            $this->Session->Messages += 1;
            $this->coffeeHouse->getForeignSessionsManager()->updateSession($this->Session);

            try
            {
                $this->coffeeHouse->getChatDialogsManager()->recordDialog(
                    $this->Session->SessionID, $this->Session->Messages, $input, $Text
                );
            }
            catch(InvalidMessageException $e)
            {
                unset($e);
            }

            $this->coffeeHouse->getDeepAnalytics()->tally('coffeehouse', 'lydia_messages', 0);

            return $Text;
        }

        /**
         * @return ForeignSession|null
         */
        public function getSession(): ?ForeignSession
        {
            return $this->Session;
        }

        /**
         * @return LocalSession|null
         */
        public function getLocalSession(): ?LocalSession
        {
            return $this->LocalSession;
        }
    }