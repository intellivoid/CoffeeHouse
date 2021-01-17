<?php


    namespace CoffeeHouse\Classes;

    use CoffeeHouse\Abstracts\ImageType;
    use CoffeeHouse\Abstracts\ServerInterfaceModule;
    use CoffeeHouse\CoffeeHouse;
    use CoffeeHouse\Exceptions\CoffeeHouseUtilsNotReadyException;
    use CoffeeHouse\Exceptions\InvalidServerInterfaceModuleException;
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
         * @return NsfwClassificationResults
         * @throws NsfwClassificationException
         * @throws UnsupportedImageTypeException
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws InvalidServerInterfaceModuleException
         */
        public function classifyImage(string &$data): NsfwClassificationResults
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

            return $ReturnResults;
        }

        /**
         * Classifies if an image is NSFW from file.
         *
         * @param string $path
         * @return NsfwClassificationResults
         * @throws CoffeeHouseUtilsNotReadyException
         * @throws InvalidServerInterfaceModuleException
         * @throws NsfwClassificationException
         * @throws PathNotFoundException
         * @throws UnsupportedImageTypeException
         */
        public function classifyImageFile(string $path): NsfwClassificationResults
        {
            if(file_exists($path) == false)
            {
                throw new PathNotFoundException("The file '$path' was not found.");
            }

            return $this->classifyImage(@file_get_contents($path));
        }
    }