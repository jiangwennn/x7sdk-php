<?php

namespace X7\Module\Common\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\Common\Constant\ApiMethod;
use X7\Request\RequestInterface;

class IpWhiteListQueryRequest implements RequestInterface
{
    /**
     * ip白名单类型
     * 
     * @var string
     */
    public $ipType;

    public function getApiMethod()
    {
        return ApiMethod::IP_WHITELIST_QUERY;
    }

    public static function make(ParamHandlerInterface $paramHandler)
    {
        $ipType = $paramHandler->getInputValue("ipType");
        if (empty($ipType) || !is_string($ipType)) {
            throw new ParameterException("ipType参数有误");
        }
        $ins = new self;
        $ins->ipType = $ipType;
        return $ins;
    }
}