<?php


    namespace CoffeeHouse\Classes;

    use CoffeeHouse\Abstracts\ImageType;
    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\DatabaseException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
    use CoffeeHouse\Exceptions\NsfwClassificationCacheNotFoundException;
    use CoffeeHouse\Exceptions\NsfwClassificationException;
    use CoffeeHouse\Exceptions\PathNotFoundException;
    use CoffeeHouse\Exceptions\ServerInterfaceException;
    use CoffeeHouse\Exceptions\UnsupportedImageTypeException;
    use CoffeeHouse\Objects\Results\NsfwClassificationResults;

    /**
     * Class NsfwClassification
     * @package CoffeeHouse\Classes
     */
    class NsfwClassification
    {
        /**
         * @var CoffeeHouse
         */
        private CoffeeHouse $coffeeHouse;

        /**
         * NsfwClassification constructor.
         * @param CoffeeHouse $coffeeHouse
         */
        public function __construct(CoffeeHouse $coffeeHouse)
        {
            $this->coffeeHouse = $coffeeHouse;
        }

        /**
         * Classifies an image data, only supported png & jpeg
         *
         * @param string $data
         * @param bool $cache
         * @return NsfwClassificationResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws InvalidServerInterfaceModuleException
         * @throws NsfwClassificationException
         * @throws UnsupportedImageTypeException
         */
        public function classifyImage(string $data, bool $cache=True): NsfwClassificationResults
        {
            $ReturnResults = new NsfwClassificationResults();

            if(Validation::isJpeg($data))
            {
                $ReturnResults->ImageType = ImageType::JPEG;
            }
            elseif(Validation::isPng($data))
            {
                $ReturnResults->ImageType = ImageType::PNG;
            }
            else
            {
                throw new UnsupportedImageTypeException("The given image must be JPEG or PNG");
            }

            $ReturnResults->ContentHash = hash("sha256", $data);
            $CacheResults = null;

            if($cache)
            {
                try
                {
                    $CacheResults = $this->coffeeHouse->getNsfwClassificationCacheManager()->getCache($ReturnResults->ContentHash);

                    if(time() - $CacheResults->LastUpdated < 172800)
                    {
                        return $CacheResults->toResults();
                    }
                }
                catch (NsfwClassificationCacheNotFoundException $e)
                {
                    unset($e);
                }
            }

            try
            {
                $Results = $this->coffeeHouse->getServerInterface()->sendRequest(
                    ServerInterfaceModule::NsfwPrediction, "/", [
                        "type" => $ReturnResults->ImageType,
                        "input" => base64_encode($data)
                    ]
                );
            }
            catch(ServerInterfaceException $serverInterfaceException)
            {
                throw new NsfwClassificationException("The classification results failed, server returned " . $serverInterfaceException->getErrorDetails());
            }

            $DecodedResults = json_decode($Results, true);
            $ReturnResults->SafePrediction = (float)$DecodedResults["results"]["safe"];
            $ReturnResults->UnsafePrediction = (float)$DecodedResults["results"]["unsafe"];
            $ReturnResults->IsNSFW = $ReturnResults->UnsafePrediction > $ReturnResults->SafePrediction;

            if($cache)
            {
                if($CacheResults !== null)
                {
                    $this->coffeeHouse->getNsfwClassificationCacheManager()->updateCache($CacheResults, $ReturnResults);
                }
                else
                {
                    $this->coffeeHouse->getNsfwClassificationCacheManager()->registerCache($ReturnResults);
                }
            }

            return $ReturnResults;
        }

        /**
         * Classifies if an image is NSFW from file.
         *
         * @param string $path
         * @param bool $cache
         * @return NsfwClassificationResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws DatabaseException
         * @throws InvalidServerInterfaceModuleException
         * @throws NsfwClassificationException
         * @throws PathNotFoundException
         * @throws UnsupportedImageTypeException
         */
        public function classifyImageFile(string $path, bool $cache=True): NsfwClassificationResults
        {
            if(file_exists($path) == false)
            {
                throw new PathNotFoundException("The file '$path' was not found.");
            }

            $file_contents = file_get_contents($path);
            return $this->classifyImage($file_contents, $cache);
        }
    }