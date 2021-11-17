<?php

namespace X7\Module\X7mall\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\X7mall\Model\IssuedProp;
use X7\Module\X7mall\Constant\ApiMethod;
use X7\Request\RequestInterface;
use X7\Utils\Traits\ToArray;

/**
 * 道具发放Request
 */
class PropIssueRequest implements RequestInterface
{

    use ToArray;

    /**
     * @var string
     */
    public $issueOrderId;

    /**
     * @var IssuedProp[]
     */
    public $issuedProps;

    /**
     * @var boolean
     */
    public $isTest = true;

    /**
     * @var string
     */
    public $roleId;

    /**
     * @var string
     */
    public $guid;

    /**
     * @var string
     */
    public $serverId;

    /**
     * @var string
     */
    public $serverName;

    /**
     * @var string
     */
    public $issueTime;

    /**
     * @var string
     */
    public $mailTitle;

    /**
     * @var string
     */
    public $mailContent;


    public function setIssuedProps($props)
    {
        $this->issuedProps = is_object($props) ? [$props] : $props;
        return $this;
    }

    public function appendIssuedProp($prop)
    {
        $this->issuedProps[] = $prop;
        return $this;
    }

    public function getApiMethod()
    {
        return ApiMethod::PROP_ISSUE;
    }


    public static function make(ParamHandlerInterface $paramHandler)
    {
        $issueOrderId = $paramHandler->getInputValue("issueOrderId");
        $roleId = $paramHandler->getInputValue('roleId');
        $mailTitle = $paramHandler->getInputValue('mailTitle');
        $mailContent = $paramHandler->getInputValue('mailContent');
        $issuedProps = $paramHandler->getInputValue('issuedProps');
        $isTest = $paramHandler->getInputValue("isTest") && true;
        $guid = $paramHandler->getInputValue("guid");
        $serverId = $paramHandler->getInputValue("serverId");
        $serverName = $paramHandler->getInputValue("serverName");
        $issueTime = $paramHandler->getInputValue("issueTime");

        if (empty($guid)) {
            throw new ParameterException("小号ID不能为空");
        }

        if (empty($serverId)) {
            throw new ParameterException("区服id不能为空");
        }

        if (empty($issueOrderId)) {
            throw new ParameterException("发放订单id不能为空");
        }

        if (empty($roleId)) {
            throw new ParameterException("角色ID不能为空");
        }

        if (!is_array($issuedProps)) {
            throw new ParameterException("发放道具信息不正确");
        }
        

        $request = new self;
        $request->issueOrderId = $issueOrderId;
        $request->roleId = $roleId;
        $request->issueTime = $issueTime; 
        $request->isTest = $isTest;
        $request->mailTitle = $mailTitle;
        $request->mailContent = $mailContent;
        $request->serverId = $serverId;
        $request->guid = $guid;
        $request->serverName = $serverName;
        foreach ($issuedProps as $prop) {
            $request->appendIssuedProp(IssuedProp::make($prop));
        }
        return $request;
    }

}