<?php

    namespace ModularAPI\HTTP;
    use Exception;
    use ModularAPI\Abstracts\HTTP\ResponseCode\ClientError;
    use ModularAPI\Abstracts\HTTP\ResponseCode\Information;
    use ModularAPI\Abstracts\HTTP\ResponseCode\Redirect;
    use ModularAPI\Abstracts\HTTP\ResponseCode\ServerError;
    use ModularAPI\Abstracts\HTTP\ResponseCode\Successful;
    use ModularAPI\Exceptions\UnsupportedClientException;
    use ModularAPI\Utilities\Checker;

    /**
     * Class Headers
     * @package ModularAPI\HTTP
     */
    class Headers
    {
        /**
         * Sets Content-Type HTTP Header
         *
         * @param string $content_type
         * @param string $name
         * @return bool
         * @throws UnsupportedClientException
         */
        public static function setContentType(string $content_type, string $name): bool
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            header("Content-Type: $content_type/$name");
            return true;
        }

        /**
         * Same as setContentType but without formatting
         *
         * @param string $content_type
         * @return bool
         * @throws UnsupportedClientException
         */
        public static function i_setContentType(string $content_type): bool
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            header("Content-Type: $content_type");
            return true;
        }

        /**
         * Sets Content-Length HTTP Header
         *
         * @param int $length
         * @return bool
         * @throws UnsupportedClientException
         */
        public static function setContentLength(int $length): bool
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            try
            {
                $ContentLength = (int)$length;
            }
            catch(Exception $exception)
            {
                $ContentLength = 0;
            }

            if($ContentLength < 0)
            {
                $ContentLength = 0;
            }

            header('Content-Length: ' . (string)$ContentLength);
            return true;
        }

        /**
         * Sets the response code
         *
         * @param int|ClientError|Information|Redirect|ServerError|Successful $responseCode
         * @return bool
         * @throws UnsupportedClientException
         */
        public static function setResponseCode(int $responseCode): bool
        {
            if(Checker::isWebRequest() == false)
            {
                throw new UnsupportedClientException();
            }

            http_response_code($responseCode);
            return true;
        }
    }