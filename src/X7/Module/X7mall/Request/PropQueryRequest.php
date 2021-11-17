<?php

namespace X7\Module\X7mall\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Request\RequestInterface;
use X7\Constant\X7mallApiMethod;
use X7\Module\X7mall\Constant\ApiMethod;

/**
 * 道具查询Request
 */
class PropQueryRequest implements RequestInterface
{

    /**
     * @var array
     */
    public $propCode;


    public function setPropCode($propCode)
    {
        if (empty($propCode) || !is_array($propCode)) {
            throw new ParameterException("道具Code不正确");
        }
        $this->propCode = $propCode;
        return $this;
    }

    public function getApiMethod()
    {
        return ApiMethod::PROP_QUERY;
    }

    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     */
    public static function make(ParamHandlerInterface $paramHandler)
    {
        return (new self)->setPropCode($paramHandler->getInputValue("propCode"));
    }
    
}