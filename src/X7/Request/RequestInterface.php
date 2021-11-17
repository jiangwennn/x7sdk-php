<?php

namespace X7\Request;

use X7\Handler\ParamHandlerInterface;

/**
 * Request Interface
 */
interface RequestInterface
{
    public function getApiMethod();

    /**
     * 校验bizParams，通过则返回请求参数Request示例
     *
     * @param ParamHandlerInterface $paramHandler
     * @return static
     */
    public static function make(ParamHandlerInterface $paramHandler);
}