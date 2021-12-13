<?php

namespace X7\Handler;

use X7\Request\Server\RequestParameterRetrieverInterface;

/**
 * 传入一个数组
 */
class ArrayParamHandler implements ParamHandlerInterface,RequestParameterRetrieverInterface
{

    protected $paramArr;

    public function __construct($paramArr)
    {
        $this->paramArr = $paramArr;
    }

    public function getInputValue($key, $formatHandler = false)
    {
        $value = isset($this->paramArr[$key]) ? $this->paramArr[$key] : "";
        return is_callable($formatHandler) ? call_user_func($formatHandler, $value) : $value; 
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->paramArr = array_merge($this->paramArr, $key);
        } else {
            $this->paramArr[$key] = $value;
        }
        return $this;
    }

    public function has($key)
    {
        return isset($this->paramArr[$key]);
    }

    public function get($key)
    {
        return $this->getInputValue($key);
    }

    public function getAll()
    {
        return $this->paramArr;
    }
    

}