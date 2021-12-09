<?php

namespace X7;

use RuntimeException;
use X7\Exception\ServerResponseException;
use X7\Module\Basic\Constant\RequestMethod;
use X7\Module\Basic\Model\BasicRequest;
use X7\Request\BasicRequestInterface;
use X7\Utils\HttpSend;
use X7\Utils\Json;

class BasicClient
{

    /**
     * 发送请求方法
     *
     * @param BasicRequestInterface $request
     * @return array
     */
    public function request(BasicRequestInterface $request)
    {
        /** @var BasicRequest */
        $basicRequest = $request->getRequest();

        if (!in_array($basicRequest->getMethod(), RequestMethod::all())) {
            throw new RuntimeException("仅支持POST和GET");
        }

        $headers = [];
        foreach ($basicRequest->getHeaders() as $key => $value) {
            $headers[] = $key . ":" . $value;
        }

        if ($basicRequest->getMethod() == RequestMethod::POST) {
            list($httpCode, $respContent) = HttpSend::curlPostSend(
                $basicRequest->getUrl(),
                $basicRequest->getBody(),
                true,
                $headers
            );
        } else {
            list($httpCode, $respContent) = HttpSend::curlGetSend(
                $basicRequest->getUrl(),
                $basicRequest->getBody(),
                true,
                $headers,
            );
        }

        if ($httpCode != "200") {
            throw new ServerResponseException("请求出错，httpCode[{$httpCode}]", $respContent);
        }

        $responseArr = Json::decode($respContent);
        if (!is_array($responseArr) || json_last_error() !== JSON_ERROR_NONE) {
            throw new ServerResponseException("请求响应数据格式错误", $respContent);
        }

        return $responseArr;
    }



}