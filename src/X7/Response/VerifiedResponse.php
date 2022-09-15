<?php

namespace X7\Response;

use X7\Exception\ServerResponseException;
use X7\Utils\Json;
use X7\Utils\Traits\ToArray;

/**
 * 通过签名校验的响应
 */
class VerifiedResponse
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


    public function __construct(ResponseBase $responseBase)
    {
        $this->bizResp = $this->validateBizResp($responseBase->bizResp);
        $this->apiMethod = $responseBase->apiMethod;
        $this->respTime = $responseBase->respTime;
        $this->appkey = $responseBase->appkey;
        $this->signature = $responseBase->signature;
        $this->gameType = $responseBase->gameType;
        $this->osType = $responseBase->osType;
    }

    private function validateBizResp($rawBizResp)
    {
        $bizResp = Json::decode($rawBizResp);

        if (is_null($bizResp) || json_last_error() != JSON_ERROR_NONE) {
            throw new ServerResponseException("响应bizResp参数不正确");
        }
        return $bizResp;
    }

}
