<?php

namespace X7\Utils;

/**
 * Json工具
 */
class Json 
{
    public static function encode($data)
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function decode($json, $isAssoc = true)
    {
        return json_decode($json, $isAssoc);
    }
}