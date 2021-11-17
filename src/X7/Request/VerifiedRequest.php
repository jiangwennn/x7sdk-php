<?php

namespace X7\Request;

use X7\Exception\ParameterException;
use X7\Utils\Json;
use X7\Utils\Traits\ToArray;
use X7\Request\RequestBase;

/**
 * 通过签名校验的request
 */
class VerifiedRequest
{
    use ToArray;

    /**
     * @var array
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


    public function __construct(RequestBase $requestBase)
    {
        $this->bizParams = $this->validateBizParams($requestBase->bizParams);
        $this->apiMethod = $requestBase->apiMethod;
        $this->reqTime = $requestBase->reqTime;
        $this->appkey = $requestBase->appkey;
        $this->signature = $requestBase->signature;
        $this->gameType = $requestBase->gameType;
        $this->osType = $requestBase->osType;
    }


    private function validateBizParams($rawBizParams)
    {
        $bizParams = Json::decode($rawBizParams);

        if (is_null($bizParams) || json_last_error() != JSON_ERROR_NONE) {
            throw new ParameterException("请求bizParams参数不正确");
        }
        return $bizParams;
    }
}