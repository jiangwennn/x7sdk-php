<?php

namespace X7\Response;

use RuntimeException;
use X7\Constant\ResponseCode;
use X7\Handler\ParamHandlerInterface;
use X7\Utils\Traits\ToArray;

/**
 * 通用业务响应
 */
class CommonResponse implements ResponseInterface
{
    use ToArray;

    public $respCode = ResponseCode::SUCCESS;

    public $respMsg = "";

    protected $apiMethod = "gateway";

    public function setApiMethod($apiMethod)
    {
        $this->apiMethod = $apiMethod;
        return $this;
    }

    /**
     * @param string $respCode
     * @return static
     */
    public function setRespCode($respCode)
    {
        $this->respCode = $respCode;
        return $this;
    }

    /**
     * @param string $respMsg
     * @return static
     */
    public function setRespMsg($respMsg)
    {
        $this->respMsg = $respMsg;
        return $this;
    }


    public function getApiMethod()
    {
        return $this->apiMethod;
    }

    /**
     * 校验
     *
     * @param ParamHandlerInterface $bizResp
     * @return static
     */
    public function validate(ParamHandlerInterface $bizResp)
    {
        $this->setRespCode($bizResp->getInputValue('respCode'))
            ->setRespMsg($bizResp->getInputValue('respMsg'));

        if ($this->respCode == ResponseCode::SUCCESS && method_exists($this, $method = "validateBizResp")) {
            call_user_func(array($this, $method), $bizResp);
        }
        return $this;
    }
}