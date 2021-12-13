<?php

namespace X7\Response;

use X7\Handler\ParamHandlerInterface;
use X7\Module\Basic\Constant\ErrorNo;

/**
 * 基础接入，接口响应
 */
class BasicResponse
{
    public $errorno = ErrorNo::SUCCESS;

    public $errormsg = "";

    public function validate(ParamHandlerInterface $paramHandler)
    {
        $this->errorno = $paramHandler->getInputValue('errorno');
        $this->errormsg = $paramHandler->getInputValue('errormsg');

        if ($this->errorno == ErrorNo::SUCCESS && $this instanceof BizRespValidatorInterface) {
            $this->validateBizResp($paramHandler);
        }
        return $this;
    }

}