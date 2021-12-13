<?php

namespace X7\Module\Basic\Response;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Response\BasicResponse;
use X7\Response\BizRespValidatorInterface;
use X7\Module\Basic\Model\LoginMemberData;

class CheckLoginResponse extends BasicResponse implements BizRespValidatorInterface
{

    /**
     * @var LoginMemberData
     */
    public $data;

    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     * @throws RuntimeException
     */
    public function validateBizResp(ParamHandlerInterface $paramHandler)
    {
        $data = $paramHandler->getInputValue("data");
        if (empty($data) || !is_array($data)) {
            throw new RuntimeException("响应参数data错误");
        }
        $this->data = LoginMemberData::make($data);
        return $this;
    }

}