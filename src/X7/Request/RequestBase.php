<?php

namespace X7\Request;

use X7\Exception\ServerRequestException;
use X7\Request\RequestInterface;
use X7\Request\Server\RequestParameterRetrieverInterface;
use X7\Utils\DateTimeUtil;
use X7\Utils\Json;
use X7\Utils\Signature;
use X7\Utils\Traits\ToArray;

/**
 * 请求基本参数
 */
class RequestBase
{

    use ToArray;

    /**
     * @var string
     */
    public $bizParams;

    /**
     * @var string
     */
    public $apiMethod;

    /**
     * @var string
     */
    public $reqTime;

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


    public function setOsType($osType)
    {
        $this->osType = $osType;
        return $this;
    }


    public function setBizParams(RequestInterface $request)
    {
        $this->bizParams = Json::encode($request);
        return $this;
    }

    public function setApiMethod($apiMethod)
    {
        $this->apiMethod = $apiMethod;
        return $this;
    }

    public function setReqTime($reqTime = null)
    {
        $this->reqTime = !empty($reqTime) ? $reqTime : DateTimeUtil::getTimeStr();
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
            $this->reqTime,
            $this->bizParams,
            $this->gameType
        );
        $this->signature = Signature::sign($payload, $rsaPrivateKey);
        return $this;
    }


    /**
     * 可选参数
     *
     * @var array
     */
    private static $optionalFields = ["osType"];


    /**
     * 验证参数是否存在
     *
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
            throw new ServerRequestException("请求参数字段不完整");
        }

        //校验类型
        foreach ($retriever->getAll() as $key => $content) {
            if (!is_string($content)) {
                throw new ServerRequestException("请求参数{$key}字段数据类型错误");
            } elseif (in_array($key, $fields)) {
                $instance->{$key} = $content;
            }
        }

        return $instance;
    }


}