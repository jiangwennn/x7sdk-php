<?php

namespace X7\Request\Server;

/**
 * post请求参数接收
 */
class PostParameterRetriever implements RequestParameterRetrieverInterface
{

    protected $parameters;

    public function __construct()
    {
        $this->parameters = $this->getAll();
    }

    public function get($key)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }

    public function has($key)
    {
        return isset($this->parameters[$key]);
    }

    public function getAll()
    {
        return $_POST;
    }

}