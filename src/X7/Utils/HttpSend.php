<?php

namespace X7\Utils;

/**
 * 发送请求
 */
class HttpSend
{

    /**
     * 发送请求方法
     *
     * @param string $url
     * @param array $postData
     * @return array
     */
    public static function curlPostSend($url, $postData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置返回值存储在变量中
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $header = array('Expect:');
        $header[] = "Content-type:application/x-www-form-urlencoded";
        $header[] = "User-Agent:x7sdk-php-2021";
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
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