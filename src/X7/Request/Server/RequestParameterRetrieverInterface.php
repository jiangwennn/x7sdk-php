<?php

namespace X7\Request\Server;

/**
 * 获取请求参数interface
 */
interface RequestParameterRetrieverInterface
{
    public function get($key);

    public function has($key);

    public function getAll();
}