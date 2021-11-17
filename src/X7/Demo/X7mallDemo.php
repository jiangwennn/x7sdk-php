<?php

namespace X7\Demo;

use Exception;
use X7\Client;
use X7\Constant\ResponseCode;
use X7\Exception\ApiExceptionInterface;
use X7\Exception\ServerRequestException;
use X7\Response\CommonResponse;
use X7\Module\X7mall\Constant\ApiMethod as X7mallApiMethod;
use X7\Exception\BusinessException;
use X7\Exception\ServerResponseException;
use X7\Handler\ArrayParamHandler;
use X7\Model\Role;
use X7\Module\X7mall\Model\IssuedProp;
use X7\Module\X7mall\Model\Prop;
use X7\Module\X7mall\Request\MallEntryRequest;
use X7\Module\X7mall\Request\PropQueryRequest;
use X7\Module\X7mall\Request\RoleQueryRequest;
use X7\Module\X7mall\Request\OrderNotifyRequest;
use X7\Module\X7mall\Request\PropIssueRequest;
use X7\Module\X7mall\Response\MallEntryResponse;
use X7\Module\X7mall\Response\OrderNotifyResponse;
use X7\Module\X7mall\Response\PropIssueResponse;
use X7\Module\X7mall\Response\RoleQueryResponse;
use X7\Module\X7mall\Response\PropQueryResponse;
use X7\Utils\Json;

/**
 * 小7商城demo
 */
class X7mallDemo
{
    /**
     * @var Client
     */
    protected $client;

    protected $requestUrl = "https://pay.x7sy.com/x7mall/gateway";

    protected $testRequestUrl = "https://pay.x7sy.com/x7mall_helper/gateway";

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function incomingRequest()
    {
        try {
            //获取通过签名校验的请求实例
            /** @var \X7\Request\VerifiedRequest */
            $verifiedRequest = $this->client->handleRequest(new \X7\Request\Server\PostParameterRetriever);

            //校验请求超时
            if (time() - strtotime($verifiedRequest->reqTime) >= 300) {
                throw new ServerRequestException("请求超时，请重试");
            }

            //自行实现接口防重放攻击
            //...

            switch ($verifiedRequest->apiMethod) {
                case X7mallApiMethod::PROP_QUERY:
                    $response = $this->handlePropQuery($verifiedRequest->bizParams);
                    break;
                case X7mallApiMethod::ROLE_QUERY:
                    $response = $this->handleRoleQuery($verifiedRequest->bizParams);
                    break;
                case X7mallApiMethod::PROP_ISSUE:
                    $response = $this->handlePropIssue($verifiedRequest->bizParams);
                    break;
                case X7mallApiMethod::ORDER_NOTIFY:
                    $response = $this->handleOrderNotify($verifiedRequest->bizParams);
                    break;
                default:
                    throw new ServerRequestException("请求apiMethod无效");
            }
        } catch (Exception $e) {
            $response = new CommonResponse;
            if (isset($verifiedRequest)) {
                $response->setApiMethod($verifiedRequest->apiMethod);
            }
            if ($e instanceof ApiExceptionInterface) {
                $response->setRespCode($e->getResponseCode())->setRespMsg($e->getMessage());
            }
        } finally {
            echo Json::encode($this->client->getResponse($response));
        }
    }


    public function sendMallEntryRequest()
    {
        try {
            $role = Role::make([
                "roleId" => "role_123",
                "guid" => "123456", // 角色所属小7小号id,
                "roleName" => "小号名称",
                "serverId" => "1",
                "serverName" => "区服名称",
                "roleLevel" => "68", // 角色等级
                "roleCE" => "999999", // 角色战力
                "roleStage" => "3-2", // 角色关卡
                "roleRechargeAmount" => 5688.66 // 角色充值总额
            ]);

            $mallEntryRequest = MallEntryRequest::make(new ArrayParamHandler(["role" => $role]));

            $verifiedResponse = $this->client->request($mallEntryRequest, $this->testRequestUrl);

            $response = (new MallEntryResponse)->validate(new ArrayParamHandler($verifiedResponse->bizResp));

            //校验请求响应状态
            if ($response->respCode != ResponseCode::SUCCESS) {
                //do something
            }

            //商城开放状态
            $response->isOpen;
            //商城红点状态
            $response->showNotification;

            var_dump($response);
            //其他逻辑
            //...
        } catch (Exception $e) {
            if ($e instanceof ServerResponseException) {
                print($e->getContext());
            }
            //异常处理
            // echo $e->getMessage();
        }
    }


    /**
     * 处理道具查询
     *
     * @param array $bizParams
     * @return PropQueryResponse
     * @throws Exception|ApiExceptionInterface
     */
    private function handlePropQuery($bizParams)
    {
        //校验参数
        $propQueryRequest = PropQueryRequest::make(new ArrayParamHandler($bizParams));

        $propQueryResponse = new PropQueryResponse;

        foreach ($propQueryRequest->propCode as $code) {
            if ($code == "NotExist") {
                throw new BusinessException("道具编码不正确");
            }
            $propQueryResponse->appendProp(Prop::make([
                //道具编码
                "propCode" => $code,
                //道具名称
                "propName" => "prop name",
                //道具描述
                "propDesc" => "this is prop desc",
                //道具图标url 或 道具图标文件路径
                "propIcon" => "https://prop.icon.png",
            ]));
        }

        return $propQueryResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("查询道具成功");
    }

    /**
     * 处理角色查询
     *
     * @param array $bizParams
     * @return RoleQueryResponse
     * @throws Exception|ApiExceptionInterface
     */
    private function handleRoleQuery($bizParams)
    {
        //校验参数
        $roleQueryRequest = RoleQueryRequest::make(new ArrayParamHandler($bizParams));

        $roleQueryResponse = new RoleQueryResponse;
        //按单个角色查询
        if (!empty($roleQueryRequest->roleId)) {
            //根据需要校验相关业务参数
            if ($roleQueryRequest->roleId == -1) {
                throw new BusinessException("角色id不存在");
            }
            $roleQueryResponse->setRole(Role::make([
                "roleId" => $roleQueryRequest->roleId,
                "guid" => "123456", // 角色所属小7小号id,
                "roleName" => "小号名称",
                "serverId" => "1",
                "serverName" => "区服名称",
                "roleLevel" => "68", // 角色等级
                "roleCE" => "999999", // 角色战力
                "roleStage" => "3-2", // 角色关卡
                "roleRechargeAmount" => 5688.66 // 角色充值总额
            ]));
        }

        //按小号批量查询
        if (!empty($roleQueryRequest->guid)) {
            $randomNum = mt_rand(1, 5);
            for ($i=$randomNum; $i>0; $i--) {
                $roleQueryResponse->appendGuidRole(Role::make([
                    "roleId" => $i,
                    "guid" => $roleQueryRequest->guid, // 角色所属小7小号id,
                    "roleName" => "小号名称" . $i,
                    "serverId" => "1",
                    "serverName" => "区服名称",
                    "roleLevel" => "68", // 角色等级
                    "roleCE" => "999999", // 角色战力
                    "roleStage" => "", // 角色关卡
                    "roleRechargeAmount" => 5688.66 // 角色充值总额
                ]));
            }
        }

        return $roleQueryResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("查询角色成功");
    }

    /**
     * 处理道具发放
     *
     * @param array $bizParams
     * @return PropIssueResponse
     * @throws Exception|ApiExceptionInterface
     */
    private function handlePropIssue($bizParams)
    {
        //校验参数
        $propIssueRequest = PropIssueRequest::make(new ArrayParamHandler($bizParams));

        $propIssueResponse = new PropIssueResponse;

        //幂等性校验，已经发放的成功的订单直接返回成功，自行实现具体逻辑
        if ($propIssueRequest->issueOrderId == "AlreadyIssued ?") {
            return $propIssueResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("此订单道具已经发放过了");
        }

        //并发校验，正在处理发放的订单需要加锁执行，自行实现具体逻辑
        if ($propIssueRequest->issueOrderId == "PropIsIssuing ?") {
            return $propIssueResponse->setRespCode(ResponseCode::FAIL)->setRespMsg("道具正在发放中");
        }

        //发放道具及邮件通知等操作...
        /** @var IssuedProp[] */
        $propIssueRequest->issuedProps;

        //其他操作..

        return $propIssueResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("道具发放成功");
    }


    /**
     * 处理订单通告
     *
     * @param array $bizParams
     * @return OrderNotifyResponse
     * @throws Exception|ApiExceptionInterface
     */
    private function handleOrderNotify($bizParams)
    {
        //校验参数
        $orderNotifyRequest = OrderNotifyRequest::make(new ArrayParamHandler($bizParams));

        $orderNotifyResponse = new OrderNotifyResponse;

        //幂等性校验，已经收到的订单通告直接返回成功，自行实现具体逻辑
        if ($orderNotifyRequest->orderId == "AlreadyHandled ?") {
            return $orderNotifyResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("此订单通告已记录过了");
        }

        //并发校验，正在处理的订单通告需要加锁执行，自行实现具体逻辑
        if ($orderNotifyRequest->orderId == "OrderHandling ?") {
            return $orderNotifyResponse->setRespCode(ResponseCode::FAIL)->setRespMsg("订单通告接收处理中");
        }

        //执行订单数据记录，其他操作...

        return $orderNotifyResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("订单通告接收成功");
    }

}