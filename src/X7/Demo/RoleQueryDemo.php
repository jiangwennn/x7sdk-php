<?php

namespace X7\Demo;

use Exception;
use X7\Client;
use X7\Constant\ResponseCode;
use X7\Exception\ApiExceptionInterface;
use X7\Exception\BusinessException;
use X7\Exception\ServerRequestException;
use X7\Handler\ArrayParamHandler;
use X7\Model\Role;
use X7\Module\Common\Constant\ApiMethod;
use X7\Module\Common\Request\RoleQueryRequest;
use X7\Module\Common\Response\RoleQueryResponse;
use X7\Request\Server\PostParameterRetriever;
use X7\Response\CommonResponse;
use X7\Utils\Json;

/**
 * 角色查询v2 demo
 */
class RoleQueryDemo
{

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function incomingRequest()
    {
        try {
            //获取通过签名校验的请求实例
            /** @var \X7\Request\VerifiedRequest */
            $verifiedRequest = $this->client->handleRequest(new PostParameterRetriever);

            //校验请求超时
            if (time() - strtotime($verifiedRequest->reqTime) >= 300) {
                throw new ServerRequestException("请求超时，请重试");
            }

            //自行实现接口防重放攻击
            //...

            switch ($verifiedRequest->apiMethod) {
                case ApiMethod::ROLE_QUERY:
                    $response = $this->handleRoleQuery($verifiedRequest->bizParams);
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
        if (!empty($roleQueryRequest->guid) || !empty($roleQueryRequest->guids)) {
            //所有小号guid一起查询
            array_push($roleQueryRequest->guids, $roleQueryRequest->guid);
            foreach ($roleQueryRequest->guids as $guid) {
                $randomNum = mt_rand(1, 3);
                for ($i = $randomNum; $i > 0; $i--) {
                    //查询结果均存入guidRoles
                    $roleQueryResponse->appendGuidRole(Role::make([
                        "roleId" => $guid."_".$i,
                        "guid" => $guid, // 角色所属小7小号id,
                        "roleName" => "小号名称" . $i,
                        "serverId" => !empty($roleQueryRequest->serverId) ? $roleQueryRequest->serverId : "S1", //如果传递了区服id，即为限定区服查询
                        "serverName" => "区服名称",
                        "roleLevel" => "68", // 角色等级
                        "roleCE" => "999999", // 角色战力
                        "roleStage" => "", // 角色关卡
                        "roleRechargeAmount" => 5688.66 // 角色充值总额
                    ]));
                }
            }
            
        }

        return $roleQueryResponse->setRespCode(ResponseCode::SUCCESS)->setRespMsg("查询角色成功");
    }


}