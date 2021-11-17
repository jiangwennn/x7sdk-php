<?php

namespace X7\Response;

use X7\Exception\ServerResponseException;
use X7\Request\Server\RequestParameterRetrieverInterface;
use X7\Utils\DateTimeUtil;
use X7\Utils\Json;
use X7\Utils\Signature;
use X7\Utils\Traits\ToArray;

/**
 * 通用响应
 */
class ResponseBase
{
    use ToArray;

    /**
     * @var ResponseInterface
     */
    public $bizResp;

    /**
     * @var string
     */
    public $apiMethod;

    /**
     * @var string
     */
    public $respTime;

    /**
     * @var string
     */
    public $appkey;

    /**
     * @var string
     */
    public $signature;

    /**
     * @var string
     */
    public $gameType;

    /**
     * @var string
     */
    public $osType = "";


    /**
     * 可选参数
     *
     * @var array
     */
    private static $optionalFields = ["osType"];


    public function setOsType($osType)
    {
        $this->osType = $osType;
        return $this;
    }


    public function setBizResp(ResponseInterface $response)
    {
        $this->bizResp = Json::encode($response);
        return $this;
    }

    public function setApiMethod($apiMethod)
    {
        $this->apiMethod = $apiMethod;
        return $this;
    }

    public function setRespTime($respTime = null)
    {
        $this->respTime = !empty($respTime) ? $respTime : DateTimeUtil::getTimeStr();
        return $this;
    }

    public function setAppkey($appkey)
    {
        $this->appkey = $appkey;
        return $this;
    }
    
    public function setGameType($gameType)
    {
        $this->gameType = $gameType;
        return $this;
    }

    public function setSignature($rsaPrivateKey)
    {
        $payload = Signature::genPayload(
            $this->apiMethod,
            $this->appkey,
            $this->respTime,
            $this->bizResp,
            $this->gameType
        );
        $this->signature = Signature::sign($payload, $rsaPrivateKey);
        return $this;
    }


    /**
     * @return self
     */
    public static function validate(RequestParameterRetrieverInterface $retriever)
    {
        $instance = new self;
        $fields = array_keys($instance->toArray());
        $diffFields = [];
        foreach ($fields as $key) {
            if (!$retriever->has($key) && !in_array($key, self::$optionalFields)) {
                $diffFields[] = $key;
            }
        }
        if (!empty($diffFields) || empty($retriever->get("signature"))) {
            throw new ServerResponseException("响应参数字段不完整");
        }

        //校验类型
        foreach ($retriever->getAll() as $key => $content) {
            if (!is_string($content)) {
                throw new ServerResponseException("响应参数{$key}字段数据类型错误");
            } elseif (in_array($key, $fields)) {
                $instance->{$key} = $content;
            }
        }

        return $instance;
    }

}