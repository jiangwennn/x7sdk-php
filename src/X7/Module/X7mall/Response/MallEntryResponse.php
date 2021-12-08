<?php

namespace X7\Module\X7mall\Response;

use X7\Handler\ParamHandlerInterface;
use X7\Module\X7mall\Constant\ApiMethod;
use X7\Response\BizRespValidatorInterface;
use X7\Response\CommonResponse;

/**
 * 商城入口请求响应
 */
class MallEntryResponse extends CommonResponse implements BizRespValidatorInterface
{

    public $isOpen = false;

    public $showNotification = false;

    protected $apiMethod = ApiMethod::MALL_ENTRY;

    /**
     * 校验
     *
     * @param ParamHandlerInterface $bizResp
     * @return self
     */
    public function validateBizResp(ParamHandlerInterface $bizResp)
    {
        $this->isOpen = $bizResp->getInputValue("isOpen") && true;
        $this->showNotification = $bizResp->getInputValue("showNotification") && true;
        return $this;
    }

}