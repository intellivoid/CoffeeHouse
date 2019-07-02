<?php


    namespace CoffeeHouse\Classes;

    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\PathScopeOutputNotFound;
    use Exception;

    /**
     * Class CustomPathScope
     * @package CoffeeHouse\Classes
     */
    class CustomPathScope
    {
        /**
         * Processes triggers for custom path scopes
         *
         * @param string $input
         * @return string|null
         * @throws PathScopeOutputNotFound
         * @throws Exception
         */
        public static function processTriggers(string $input)
        {
            $CPS = CoffeeHouse::getCustomPathScopes();

            foreach($CPS['triggers'] as $Trigger => $Properties)
            {
                $Calculation = levenshtein($input, $Trigger);
                if($Calculation < $Properties['max_points'])
                {
                    if($Properties['random_factor'] == true)
                    {
                        $RandomFactor = (bool)random_int(0, 1);
                        if($RandomFactor == true)
                        {
                            return self::getOutput($Properties['output']);
                        }
                    }
                }
            }

            return null;
        }

        /**
         * Gets a random output
         *
         * @param string $output
         * @return string
         * @throws PathScopeOutputNotFound
         */
        public static function getOutput(string $output): string
        {
            $CPS = CoffeeHouse::getCustomPathScopes();

            if(isset($CPS['outputs'][$output]) == false)
            {
                throw new PathScopeOutputNotFound();
            }

            $SelectedOutput = array_rand($CPS['outputs'][$output]);
            return $CPS['outputs'][$output][$SelectedOutput];
        }
    }