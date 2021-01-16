<?php


    namespace CoffeeHouse\Classes;

    use CoffeeHouse\Abstracts\TranslateProcessingEngine;
    use CoffeeHouse\Abstracts\TranslationCacheSearchMethod;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\EngineNotImplementedException;
    use CoffeeHouse\Exceptions\InvalidLanguageException;
    use CoffeeHouse\Exceptions\InvalidSearchMethodException;
    use CoffeeHouse\Exceptions\InvalidTextInputException;
    use CoffeeHouse\Exceptions\TranslationCacheNotFoundException;
    use CoffeeHouse\Exceptions\TranslationException;
    use CoffeeHouse\Objects\Results\TranslationResults;
    use Exception;
    use Stichoza\GoogleTranslate\GoogleTranslate;

    /**
     * Class Translator
     * @package CoffeeHouse\Classes
     */
    class Translator
    {
        /**
         * @var GoogleTranslate|null
         */
        private $GoogleTranslate;

        /**
         * The last refresh timestamp
         *
         * @var int|null
         */
        private int $LastRefresh;

        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * Translator constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
            $this->LastRefresh = (int)time();
        }

        /**
         * Determines if a refresh is needed
         *
         * @param int $refresh_time
         * @return bool
         */
        public function needsRefresh(int $refresh_time=1200): bool
        {
            if($this->LastRefresh == null)
            {
                $this->LastRefresh = (int)time() + $refresh_time;
                return false;
            }

            if((int)time() > $this->LastRefresh)
            {
                $this->LastRefresh = (int)time() + $refresh_time;
                return true;
            }

            return false;
        }

        /**
         * Translates the given text in the appropriate engine
         *
         * @param string $input The text to translate
         * @param string $output The output language to translate to
         * @param string $source The source of the language of the input text
         * @param string $engine The engine to use
         * @param bool $use_cache If cache should be used
         * @return TranslationResults
         * @throws DatabaseException
         * @throws EngineNotImplementedException
         * @throws InvalidLanguageException
         * @throws InvalidSearchMethodException
         * @throws InvalidTextInputException
         * @throws TranslationCacheNotFoundException
         * @throws TranslationException
         */
        public function translate(string $input, string $output, string $source, string $engine="auto", bool $use_cache=True): TranslationResults
        {
            $cache = null;
            $update_cache = false;
            $cache_hash = Hashing::translateCachePublicId($source, $output, $input);

            $output = Utilities::convertToISO6391($output);
            $source = Utilities::convertToISO6391($source);

            if($use_cache)
            {
                try
                {
                    $cache = $this->coffeeHouse->getTranslationCacheManager()->getCache(TranslationCacheSearchMethod::byPublicID, $cache_hash);
                }
                catch(TranslationCacheNotFoundException $translationCacheNotFoundException)
                {
                    unset($translationCacheNotFoundException);
                }

                if($cache !== null)
                {
                    if((int)time() - $cache->LastUpdatedTimestamp > 172800)
                    {
                        $update_cache = true;
                    }
                    else
                    {
                        return TranslationResults::fromCache($cache);
                    }
                }
            }

            $translation_results = null;
            if($engine == "auto")
                $engine = TranslateProcessingEngine::GoogleTranslate;

            switch($engine)
            {
                case TranslateProcessingEngine::GoogleTranslate:
                    $translation_results = $this->GoogleTranslate($input, $output, $source);
                    break;

                case TranslateProcessingEngine::CoffeeHouseTranslate:
                default:
                    throw new EngineNotImplementedException("The engine '$engine' is not implemented");
            }

            if($use_cache)
            {
                if($update_cache)
                {
                    $cache->Output = $translation_results->Output;
                    $this->coffeeHouse->getTranslationCacheManager()->updateCache($cache);
                }
                else
                {
                    $this->coffeeHouse->getTranslationCacheManager()->registerCache($translation_results);
                }
            }


            return $translation_results;
        }

        /**
         * Translates using Google Translate
         *
         * @param string $input
         * @param string $target
         * @param string $source
         * @return TranslationResults
         * @throws InvalidTextInputException
         * @throws TranslationException
         */
        public function googleTranslate(string $input, string $target, string $source=""): TranslationResults
        {
            if(Validation::translationInput($input) == false)
            {
                throw new InvalidTextInputException("The given input cannot be empty");
            }

            if($this->needsRefresh())
            {
                $this->GoogleTranslate = new GoogleTranslate();
            }

            if($this->GoogleTranslate == null)
            {
                $this->GoogleTranslate = new GoogleTranslate();
            }

            $results = new TranslationResults();
            $results->ProcessingEngine = TranslateProcessingEngine::GoogleTranslate;
            $results->Source = $source;
            $results->Target = $target;
            $results->Input = $input;

            try
            {
                if(strlen($results->Input) > 5000)
                {
                    // To process this, we need to split it in batches.
                    $batch_query = str_split($results->Input, 5000);
                    $results->Output = "";

                    foreach($batch_query as $item)
                        $results->Output .= $this->GoogleTranslate->setTarget($target)->setSource($source)->translate($item);
                }
                else
                {
                    $results->Output = $this->GoogleTranslate->setTarget($target)->setSource($source)->translate($input);
                }

                if(strlen($results->Output) == 0)
                {
                    throw new TranslationException("The Translation engine returned empty results");
                }

            }
            catch(Exception $e)
            {
                throw new TranslationException("There was an error with the Translation Engine", 0, $e);
            }

            return $results;
        }
    }