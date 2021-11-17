<?php

namespace X7\Module\Common\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\Common\Constant\ApiMethod;
use X7\Request\RequestInterface;

class RoleQueryRequest implements RequestInterface
{
    /**
     * 角色id
     * 
     * @var string
     */
    public $roleId = "";

    /**
     * 小号id
     *
     * @var string
     */
    public $guid = "";


    /**
     * 多个小号id
     *
     * @var array
     */
    public $guids = [];

    /**
     * 区服id
     *
     * @var string
     */
    public $serverId = "";


    public function getApiMethod()
    {
        return ApiMethod::ROLE_QUERY;
    }

    public static function make(ParamHandlerInterface $paramHandler)
    {
        $roleId = $paramHandler->getInputValue("roleId");
        $guid = $paramHandler->getInputValue("guid");
        $guids = $paramHandler->getInputValue("guids");
        if (empty($roleId) && empty($guid) && empty($guids)) {
            throw new ParameterException("guid、guids和roleId至少需要一个");
        }
        if (!is_array($guids)) {
            throw new ParameterException("guids数据类型有误");
        }
        $request = new self;
        $request->guids = $guids;
        $request->guid = $guid;
        $request->roleId = $roleId;
        $request->serverId = $paramHandler->getInputValue('serverId');
        return $request;
    }
}