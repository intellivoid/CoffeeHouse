<?php

    namespace ModularAPI\Utilities;

    /**
     * Class Hashing
     * @package ModularAPI\Utilities
     */
    class Hashing
    {
        /**
         * Peppers a hash using whirlpool
         *
         * @param string $Data The hash to pepper
         * @param int $Min Minimal amounts of executions
         * @param int $Max Maximum amount of executions
         * @return string
         */
        public static function pepper(string $Data, int $Min = 100, int $Max = 1000): string
        {
            $n = rand($Min, $Max);
            $res = '';
            $Data = hash('whirlpool', $Data);
            for ($i=0,$l=strlen($Data) ; $l ; $l--)
            {
                $i = ($i+$n-1) % $l;
                $res = $res . $Data[$i];
                $Data = ($i ? substr($Data, 0, $i) : '') . ($i < $l-1 ? substr($Data, $i+1) : '');
            }
            return($res);
        }


        /**
         * Constructs a unique month ID
         *
         * @param int $month
         * @param int $year
         * @return string
         */
        public static function calculateMonthID(int $month, int $year): string
        {
            $c_month = hash('sha256', (string)$month);
            $c_year = hash('sha256', (string)$year);

            return hash('haval256,3', "s_month=$c_month/$c_year");
        }

        /**
         * Builds a full certificate key used as an alternative form of authentication
         *
         * @param string $IssuerName
         * @param string $PrivateSignature
         * @param string $PublicSignature
         * @return string
         */
        public static function buildCertificateKey(string $IssuerName, string $PrivateSignature, string $PublicSignature): string
        {
            $KeyPart1 = hash('whirlpool', hash('haval256,4', $IssuerName) . hash('sha256', $PrivateSignature));
            $KeyPart2 = hash('whirlpool', hash('haval256,3', $IssuerName) . hash('sha256', $PublicSignature));
            $KeyPart3 = hash('whirlpool', $KeyPart1);
            $KeyPart4 = hash('whirlpool', $KeyPart2);
            $KeyPart5 = hash('haval128,4', hash('haval192,5', $KeyPart1) . hash('haval224,5', $KeyPart2));
            $KeyPart6 = hash('crc32b', hash('haval192,5', $KeyPart3) . hash('sha256', $KeyPart1));

            $IssuerName = strtolower($IssuerName);
            $IssuerName = str_ireplace(' ', '-', $IssuerName);

            return("$KeyPart1$KeyPart2($KeyPart3$KeyPart4)^$KeyPart5-$KeyPart6/$IssuerName");
        }

        /**
         * Calculates the Public ID from the Certificate Key
         *
         * @param string $certificateKey
         * @return string
         */
        public static function calculatePublicKey(string $certificateKey): string
        {
            $fpKey = hash('sha256', $certificateKey);
            $spKey = hash('crc32b', $fpKey);
            return $fpKey . $spKey;
        }

        /**
         * Calculates the access key
         *
         * @param string $privateSignature
         * @param string $publicSignature
         * @param string $timeSignature
         * @return string
         */
        public static function calculatePublicID(string $privateSignature, string $publicSignature, string $timeSignature): string
        {
            return hash('haval256,4', self::pepper($privateSignature) . hash('haval160,5', self::pepper($publicSignature)) . hash('sha256', self::pepper($timeSignature)));
        }

        /**
         * Generates a Time Signature
         *
         * @param int $time
         * @param string $issuer
         * @return string
         */
        public static function generateTimeSignature(int $time, string $issuer): string
        {
            $call_time = self::pepper((string)$time);
            $issuer_pepper = self::pepper($issuer);

            return hash('sha256', $call_time . $issuer_pepper);
        }

        /**
         * Generates a private signature
         *
         * @param string $timeSignature
         * @param string $issuer
         * @param int $time
         * @return string
         */
        public static function generatePrivateSignature(string $timeSignature, string $issuer, int $time): string
        {
            $call_time = self::pepper((string)$time);
            $issuer_pepper = self::pepper($issuer);

            return hash('sha256', $call_time . $issuer_pepper . $timeSignature);
        }

        /**
         * Generates a public signature
         *
         * @param string $timeSignature
         * @param string $privateSignature
         * @return string
         */
        public static function generatePublicSignature(string $timeSignature, string $privateSignature): string
        {
            return hash('tiger128,3', $timeSignature . $privateSignature);
        }

        /**
         * Calculates the reference ID
         *
         * @param int $timestamp
         * @param string $version
         * @param string $module
         * @param string $ip
         * @return string
         */
        public static function calculateReferenceID(int $timestamp, string $version, string $module, string $ip)
        {
            return hash('sha256',  self::pepper($timestamp) . self::pepper($version) . self::pepper($module) . $ip);
        }

    }