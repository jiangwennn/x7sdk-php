<?php

namespace X7\Utils;

class ParamTool
{

    public static function buildQueryString($queryArr, $unsetEmpty = true, $urlEncode = false)
    {
        if (empty($queryArr)) {
            return "";
        }

        if ($unsetEmpty) {
            $queryString = http_build_query($queryArr);
            if (!$urlEncode) {
                $queryString = urldecode($queryString);
            }
        } else {
            $queryStringArr = array();
            foreach ($queryArr as $key => $value) {
                $queryStringArr[$key] = "{$key}={$value}";
            }
            $queryString = implode("&", $queryStringArr);
            if ($urlEncode) {
                $queryString = urlencode($queryString);
            }
        }
        return $queryString;
    }


    public static function isNumber($num)
    {
        return self::isIntegerNumber($num) || self::isFloatNumber($num);
    }

    public static function isFloatNumber($num)
    {
        return preg_match("/^\d+\.\d+$/", $num);
    }


    public static function isIntegerNumber($num)
    {
        return preg_match("/^\d+$/", $num);
    }

    public static function isValidAppkey($appkey)
    {
        return preg_match("/^(x7sy)?[0-9a-z]{32}$/", $appkey);
    }


    /**
     * 校验是否为有效的公钥
     *
     * @param string $rsaPublicKey
     * @return boolean
     */
    public static function isValidPublicKey($rsaPublicKey)
    {
        $pubKey = openssl_pkey_get_public(Signature::formatRsaPublicKey($rsaPublicKey));
        return !empty($pubKey);
    }

    /**
     * 校验是否为有效的私钥
     *
     * @param string $rsaPublicKey
     * @return boolean
     */
    public static function isValidPrivateKey($rsaPrivateKey)
    {
        $privKey = openssl_pkey_get_private(Signature::formatRsaPrivateKey($rsaPrivateKey));
        return !empty($privKey);
    }

}