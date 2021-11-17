<?php

namespace X7\Handler;

/**
 * 参数获取处理器interface
 */
interface ParamHandlerInterface
{
    public function getInputValue($key, $paramFilter = null);
}