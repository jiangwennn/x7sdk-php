<?php

namespace X7\Demo;

use Exception;
use RuntimeException;
use X7\BasicClient;
use X7\Constant\ResponseCode;
use X7\Exception\BusinessException;
use X7\Exception\ServerResponseException;
use X7\Handler\ArrayParamHandler;
use X7\Module\Basic\Constant\ErrorNo;
use X7\Module\Basic\Model\GamePay;
use X7\Module\Basic\Request\CheckGameOrderRequest;
use X7\Module\Basic\Request\CheckLoginRequest;
use X7\Module\Basic\Request\GamePayCallbackRequest;
use X7\Module\Basic\Response\CheckGameOrderResponse;
use X7\Module\Basic\Response\CheckLoginResponse;
use X7\Utils\ParamTool;

class BasicClientDemo
{

    protected $client;

    public function __construct()
    {
        $this->client = new BasicClient;
    }


    public function sendCheckLoginRequest($appkey, $tokenkey, $requestUrl = "")
    {
        try {
            $paramHandler = new ArrayParamHandler([
                "appkey" => $appkey,
                "tokenkey" => $tokenkey,
            ]);

            //可选，不使用默认的请求地址
            if (!empty($requestUrl)) {
                $paramHandler->set("requestUrl", $requestUrl);
            }

            $request = CheckLoginRequest::make($paramHandler);

            $responseArr = $this->client->request($request);
            $response = (new CheckLoginResponse)->validate(new ArrayParamHandler($responseArr));

            //判断是否成功
            if ($response->errorno != ErrorNo::SUCCESS) {
                throw new RuntimeException("请求失败：". $response->errormsg);
            }

            // do something
            
            //用户数据
            $response->data->guid;

            var_dump($response);
        } catch (Exception $e) {
            if ($e instanceof ServerResponseException) {
                echo $e->getContext();
            }
            echo $e->getMessage();
        }
    }


    public function gamePayRequest($x7PublicKey)
    {
        $requestArr = (new GamePay)
            ->setGameOrderId("X7GC" . mt_rand(111, 999) . "TEST")
            ->setGameArea("S1")
            ->setGameGuid("6656555")
            ->setGameLevel("200")
            ->setGamePrice("648.00")
            ->setGameRoleId("R101")
            ->setGameRoleName("小可爱101")
            ->setNotifyId(-1)
            ->setSubject("大宝剑")
            ->setExtendsInfoData(ParamTool::buildQueryString(["key" => "value"]))
            ->done($x7PublicKey);
        
        return $requestArr;
    }


    public function sendCheckGameOrderRequest($appkey, $gameOrderId, $x7PublicKey, $requestUrl = "")
    {
        try {
            $paramHandler = new ArrayParamHandler([
                "appkey" => $appkey,
                "gameOrderId" => $gameOrderId,
                "x7PublicKey" => $x7PublicKey
            ]);

            //可选，不使用默认的请求地址
            if (!empty($requestUrl)) {
                $paramHandler->set("requestUrl", $requestUrl);
            }

            $request = CheckGameOrderRequest::make($paramHandler);

            $responseArr = $this->client->request($request);
            $response = (new CheckGameOrderResponse)->validate(new ArrayParamHandler($responseArr));

            //判断是否成功
            if ($response->errorno != ErrorNo::SUCCESS) {
                throw new RuntimeException("请求失败：" . $response->errormsg);
            }

            $response->game_order_data;

            var_dump($response);
        } catch (Exception $e) {
            if ($e instanceof ServerResponseException) {
                echo $e->getContext();
            }
            echo $e->getMessage();
        }
    }


    public function handleGamePayCallback($x7PublicKey)
    {
        try {
            /** @var GamePayCallbackRequest */
            $request = $this->client->handleRequest(
                new GamePayCallbackRequest($x7PublicKey), 
                new ArrayParamHandler($_POST)
            );
    
            $gamePayEncrypData = $request->getDecryptedData();

            //订单参数
            $request->game_orderid;
            

            //幂等性校验，已经发放的成功的订单直接返回成功，自行实现具体逻辑
            if ($request->game_orderid == "SUCCESS ?") {
                throw new BusinessException(ResponseCode::SUCCESS);
            }

            //并发校验，正在处理发放的订单需要加锁执行，自行实现具体逻辑
            if ($request->game_orderid == "PropIsIssuing ?") {
                return new BusinessException("failed:order processing");
            }

            
            $request->xiao7_goid;

            // do something

            //加密参数校验
            $gamePayEncrypData->game_orderid;
            $gamePayEncrypData->guid;
            $gamePayEncrypData->pay_price;
            // 示例
            // if (bccomp($gamePayEncrypData->pay_price, 648, 2) != 0) {
            //     throw new BusinessException("failed:pay_price error");
            // }


            // 发放道具

            $result = ResponseCode::SUCCESS;
        } catch (Exception $e) {
            $result = $e->getMessage();
        } finally {
            //响应
            echo $result = strtolower($result);
            return $result;
        }

    }

}

