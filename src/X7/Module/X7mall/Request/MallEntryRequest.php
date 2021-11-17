<?php

namespace X7\Module\X7mall\Request;

use X7\Handler\ParamHandlerInterface;
use X7\Model\Role;
use X7\Request\RequestInterface;
use X7\Module\X7mall\Constant\ApiMethod;

/**
 * 商城入口Request
 */
class MallEntryRequest implements RequestInterface
{

    /**
     * @var Role
     */
    public $role;


    public function getApiMethod()
    {
        return ApiMethod::MALL_ENTRY;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     */
    public static function make(ParamHandlerInterface $paramHandler)
    {
        return (new self)->setRole($paramHandler->getInputValue("role"));
    }

}