<?php

namespace X7\Response;

use X7\Handler\ParamHandlerInterface;

/**
 * 响应interface
 */
interface ResponseInterface
{
    public function getApiMethod();

    /**
     * @param ParamHandlerInterface $bizResp
     * @return ResponseInterface
     */
    public function validate(ParamHandlerInterface $bizResp);
}
