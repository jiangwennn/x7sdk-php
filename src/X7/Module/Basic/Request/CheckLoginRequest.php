<?php

namespace X7\Module\Basic\Request;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\Basic\Constant\RequestMethod;
use X7\Module\Basic\Model\BasicRequest;
use X7\Request\BasicRequestInterface;
use X7\Utils\ParamTool;

/**
 * SDK登录获取用户信息
 */
class CheckLoginRequest implements BasicRequestInterface
{
    protected $appkey;

    protected $requestUrl = "https://api.x7s.com/user/check_v4_login";

    public $tokenkey;

    public $sign;

    public static function make(ParamHandlerInterface $paramHandler)
    {
        $appkey = $paramHandler->getInputValue("appkey");
        $tokenkey = $paramHandler->getInputValue("tokenkey");
        $requestUrl = $paramHandler->getInputValue("requestUrl");
        
        if (empty($appkey) || !is_string($appkey) || !ParamTool::isValidAppkey($appkey)) {
            throw new RuntimeException("appkey参数有误");
        }
        if (empty($tokenkey) || !is_string($tokenkey)) {
            throw new RuntimeException("tokenkey参数有误");
        }

        $self = new self;
        if (!empty($requestUrl)) {
            $self->requestUrl = $requestUrl;
        }
        $self->appkey = $appkey;
        $self->tokenkey = $tokenkey;
        $self->sign = md5($appkey . $tokenkey);
        return $self;
    }


    public function getRequest()
    {
        return (new BasicRequest)
            ->setUrl($this->requestUrl)
            ->setMethod(RequestMethod::POST)
            ->setBody([
                "tokenkey" => $this->tokenkey,
                "sign" => $this->sign
            ]);
    }
}