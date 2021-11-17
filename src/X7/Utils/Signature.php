<?php

namespace X7\Utils;

/**
 * 验签工具
 */
class Signature
{

    public static function sign($content, $rsaPrivateKey, $algo = OPENSSL_ALGO_SHA256)
    {
        $formatPrivateKey = self::formatRsaPrivateKey($rsaPrivateKey);
        openssl_sign($content, $signature, $formatPrivateKey, $algo);
        return base64_encode($signature);
    }

    public static function verify($content, $signature, $rsaPublicKey, $algo = OPENSSL_ALGO_SHA256)
    {
        $rawSignature = base64_decode($signature);
        $formatPublicKey = self::formatRsaPublicKey($rsaPublicKey);
        return openssl_verify($content, $rawSignature, $formatPublicKey, $algo);
    }

    public static function genPayload($apiMethod, $appkey, $datetime, $body, $gameType, $method = "POST")
    {
        $payload = $method . " " . $apiMethod . "@" . $appkey . "#" .$gameType . "." 
            . $datetime . "\n\n" . $body;
        return $payload;
    }


    public static function formatRsaPublicKey($publicKey)
    {
        return "-----BEGIN PUBLIC KEY-----\r\n" . wordwrap($publicKey, 64, "\r\n", TRUE) . "\r\n-----END PUBLIC KEY-----";
    }

    public static function formatRsaPrivateKey($privateKey)
    {
        return "-----BEGIN RSA PRIVATE KEY-----\r\n" . wordwrap($privateKey, 64, "\r\n", TRUE) . "\r\n-----END RSA PRIVATE KEY-----";
    }

    


}