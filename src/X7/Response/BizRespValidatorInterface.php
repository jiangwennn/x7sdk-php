<?php

namespace X7\Response;

use X7\Handler\ParamHandlerInterface;
use RuntimeException;

interface BizRespValidatorInterface
{
    /**
     * 校验
     *
     * @param ParamHandlerInterface $bizResp
     * @return self
     * @throws RuntimeException
     */
    public function validateBizResp(ParamHandlerInterface $bizResp);
}