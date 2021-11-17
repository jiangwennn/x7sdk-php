<?php

namespace X7\Module\X7mall\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\X7mall\Constant\ApiMethod;
use X7\Request\RequestInterface;

/**
 * 角色查询Request
 */
class RoleQueryRequest implements RequestInterface
{

    public $roleId;

    public $guid;

    public function getApiMethod()
    {
        return ApiMethod::ROLE_QUERY;
    }

    public static function make(ParamHandlerInterface $paramHandler)
    {
        $roleId = $paramHandler->getInputValue("roleId");
        $guid = $paramHandler->getInputValue("guid", true);
        if (empty($roleId) && empty($guid)) {
            throw new ParameterException("guid和roleId不可同时为空");
        }

        $request = new self;
        $request->guid = $guid;
        $request->roleId = $roleId;
        return $request;
    }

}