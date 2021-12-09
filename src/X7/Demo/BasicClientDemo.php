<?php

namespace X7\Demo;

use Exception;
use RuntimeException;
use X7\BasicClient;
use X7\Exception\ServerResponseException;
use X7\Handler\ArrayParamHandler;
use X7\Module\Basic\Constant\ErrorNo;
use X7\Module\Basic\Model\GamePay;
use X7\Module\Basic\Request\CheckLoginRequest;
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


    public function gamePayRequest($x7RsaPublicKey)
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
            ->done($x7RsaPublicKey);
        
        return $requestArr;
    }

}

