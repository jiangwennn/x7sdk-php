<?php

namespace X7\Utils;

/**
 * 发送请求
 */
class HttpSend
{

    /**
     * 发送POST请求
     *
     * @param string $url
     * @param array $postData
     * @param bool $urlEncode
     * @param array $customHeaders
     * @return array
     */
    public static function curlPostSend($url, $postData, $urlEncode = true, $customHeaders = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置返回值存储在变量中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $header = array('Expect:');
        $header[] = "User-Agent: x7sdk-php-2021";
        if ($urlEncode) {
            $header[] = "Content-Type: application/x-www-form-urlencoded";
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        } else {
            if (empty($customHeaders)) {
                $header[] = "Content-Type: multipart/form-data";
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        if (!empty($customHeaders)) {
            array_push($header, ...$customHeaders);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $output = curl_exec($ch);
        $httpStateArr = curl_getinfo($ch);
        curl_close($ch);
        return array($httpStateArr["http_code"], $output);
    }


    /**
     * 发送GET请求
     *
     * @param string $url
     * @param array $queryArr
     * @param boolean $urlEncode
     * @param array $customHeaders
     * @return array
     */
    public static function curlGetSend($url, $queryArr = [], $urlEncode = true, $customHeaders = [])
    {
        if (!empty($queryArr)) {
            $queryString = $urlEncode ? http_build_query($queryArr) : urldecode(http_build_query($queryArr));
            $url = strripos($url, "?") !== false ? $url . "&" . $queryString : $url . "?" . $queryString;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置返回值存储在变量中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $header = array('Expect:');
        $header[] = "User-Agent: x7sdk-php-2021";
        if (!empty($customHeaders)) {
            array_push($header, ...$customHeaders);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $output = curl_exec($ch);
        $httpStateArr = curl_getinfo($ch);
        curl_close($ch);
        return array($httpStateArr["http_code"], $output);
    }

}