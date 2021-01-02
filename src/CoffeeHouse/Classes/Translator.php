<?php


    namespace CoffeeHouse\Classes;

    use ErrorException;
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
        private GoogleTranslate $GoogleTranslate;

        /**
         * The last refresh timestamp
         *
         * @var int|null
         */
        private int $LastRefresh;

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
         * Translates using Google Translate
         *
         * @param string $input
         * @param string $target
         * @param string $source
         * @param bool $use_cache
         * @return string
         * @throws ErrorException
         */
        public function googleTranslate(string $input, string $target, string $source="", bool $use_cache=True): string
        {
            if($this->needsRefresh())
            {
                $this->GoogleTranslate = new GoogleTranslate();
            }

            if($this->GoogleTranslate == null)
            {
                $this->GoogleTranslate = new GoogleTranslate();
            }

            return $this->GoogleTranslate->setTarget($target)->setSource($source)->translate($input);
        }
    }