<?php

namespace X7;

use RuntimeException;
use X7\Constant\GameType;
use X7\Constant\OsType;
use X7\Exception\ApiExceptionInterface;
use X7\Exception\ServerRequestException;
use X7\Exception\ServerResponseException;
use X7\Exception\SignatureException;
use X7\Handler\ArrayParamHandler;
use X7\Request\RequestBase;
use X7\Request\RequestInterface;
use X7\Request\Server\RequestParameterRetrieverInterface;
use X7\Request\VerifiedRequest;
use X7\Response\CommonResponse;
use X7\Response\ResponseBase;
use X7\Response\VerifiedResponse;
use X7\Utils\HttpSend;
use X7\Utils\Json;
use X7\Utils\ParamTool;
use X7\Utils\Signature;

class Client
{
    protected $appkey;

    protected $gameRsaPrivateKey;

    protected $x7PublicKey;

    protected $gameType;

    protected $osType = "";

    public function __construct($appkey, $gameRsaPrivateKey, $x7PublicKey, $gameType, $osType = null)
    {
        $this->setAppkey($appkey)
            ->setGameRsaPrivateKey($gameRsaPrivateKey)
            ->setX7PublicKey($x7PublicKey)
            ->setGameType($gameType);

        if (!empty($osType)) {
            $this->setOsType($osType);
        }
    }

    public function setAppkey($appkey)
    {
        if (!ParamTool::isValidAppkey($appkey)) {
            throw new RuntimeException("appkey参数无效");
        }
        $this->appkey = $appkey;
        return $this;
    }

    public function setGameRsaPrivateKey($gameRsaPrivateKey)
    {
        if (!ParamTool::isValidPrivateKey($gameRsaPrivateKey)) {
            throw new RuntimeException("gameRsaPrivateKey参数无效");
        }
        $this->gameRsaPrivateKey = $gameRsaPrivateKey;
        return $this;
    }

    public function setX7PublicKey($x7PublicKey)
    {
        if (!ParamTool::isValidPublicKey($x7PublicKey)) {
            throw new RuntimeException("x7PublicKey参数无效");
        }
        $this->x7PublicKey = $x7PublicKey;
        return $this;
    }

    public function setGameType($gameType)
    {
        if (!in_array($gameType, GameType::all())) {
            throw new RuntimeException("gameType参数无效");
        }
        $this->gameType = $gameType;
        return $this;
    }

    public function setOsType($osType)
    {
        if (!in_array($osType, OsType::all())) {
            throw new RuntimeException("osType参数无效");
        }
        $this->osType = $osType;
        return $this;
    }


    /**
     * 接收请求
     *
     * @param RequestParameterRetrieverInterface|null $retriever
     * @return VerifiedRequest
     * @throws Exception|ApiExceptionInterface
     */
    public function handleRequest(RequestParameterRetrieverInterface $retriever = null)
    {
        if (is_null($retriever)) {
            $retriever = new RequestParameterRetrieverInterface;
        }

        $request = RequestBase::validate($retriever);
        
        if ($request->appkey != $this->appkey) {
            throw new ServerRequestException("请求来源appkey不一致");
        }

        $payload = Signature::genPayload(
            $request->apiMethod,
            $request->appkey,
            $request->reqTime,
            $request->bizParams,
            $request->gameType
        );

        if (!Signature::verify($payload, $request->signature, $this->x7PublicKey)) {
            throw new SignatureException("请求签名验证不通过");
        }
        return new VerifiedRequest($request);
    }

    /**
     * 生成响应
     *
     * @param CommonResponse $commonResponse
     * @return ResponseBase
     */
    public function getResponse(CommonResponse $commonResponse)
    {
        return (new ResponseBase)
            ->setApiMethod($commonResponse->getApiMethod())
            ->setAppkey($this->appkey)
            ->setOsType($this->osType)
            ->setRespTime()
            ->setBizResp($commonResponse)
            ->setGameType($this->gameType)
            ->setSignature($this->gameRsaPrivateKey);
    }

    /**
     * 发起请求
     *
     * @param RequestInterface $request
     * @param string $requestUrl
     * @return VerifiedResponse
     * @throws ServerResponseException
     */
    public function request(RequestInterface $request, $requestUrl)
    {
        $requestBase = (new RequestBase)
            ->setAppkey($this->appkey)
            ->setApiMethod($request->getApiMethod())
            ->setBizParams($request)
            ->setGameType($this->gameType)
            ->setOsType($this->osType)
            ->setReqTime()
            ->setSignature($this->gameRsaPrivateKey);

        list($httpCode, $output) = HttpSend::curlPostSend($requestUrl, $requestBase->toArray());
        
        if ($httpCode != "200") {
            throw new ServerResponseException("请求出错，httpCode[{$httpCode}]", $output);
        }

        $outputArr = Json::decode($output);
        if (is_null($outputArr) || json_last_error() !== JSON_ERROR_NONE) {
            throw new ServerResponseException("请求响应数据格式错误", $output);
        }

        $responseBase = (new ResponseBase)->validate(new ArrayParamHandler($outputArr));

        $payload = Signature::genPayload(
            $responseBase->apiMethod,
            $responseBase->appkey,
            $responseBase->respTime,
            $responseBase->bizResp,
            $responseBase->gameType
        );

        if (!Signature::verify($payload, $responseBase->signature, $this->x7PublicKey)) {
            throw new ServerResponseException("请求响应签名验证不通过", $output);
        }

        return new VerifiedResponse($responseBase);
    }

    public function response(ResponseBase $response)
    {
        return Json::encode($response);
    }
}