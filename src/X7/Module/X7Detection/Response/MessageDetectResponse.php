<?php

namespace X7\Module\X7Detection\Response;

use X7\Handler\ParamHandlerInterface;
use X7\Module\X7Detection\Constant\ApiMethod;
use X7\Response\CommonResponse;
use X7\Module\X7Detection\Model\DetectResult;

class MessageDetectResponse extends CommonResponse
{

    /**
     * @var DetectResult
     */
    public $detectResult;


    protected $apiMethod = ApiMethod::MESSAGE_DETECT;

    /**
     * 校验
     *
     * @param ParamHandlerInterface $bizResp
     * @return self
     * @throws RuntimeException
     */
    public function validateBizResp(ParamHandlerInterface $bizResp)
    {
        $this->detectResult = DetectResult::make($bizResp->getInputValue("detectResult"));
        return $this;
    }


}