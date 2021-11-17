<?php

namespace X7\Demo;

use Exception;
use X7\Client;
use X7\Constant\ResponseCode;
use X7\Exception\ServerResponseException;
use X7\Handler\ArrayParamHandler;
use X7\Module\X7Detection\Constant\LabelCode;
use X7\Module\X7Detection\Constant\Level;
use X7\Module\X7Detection\Constant\OperateType;
use X7\Module\X7Detection\Request\MessageDetectReportRequest;
use X7\Module\X7Detection\Request\MessageDetectRequest;
use X7\Module\X7Detection\Response\MessageDetectReportResponse;
use X7\Module\X7Detection\Response\MessageDetectResponse;

/**
 * 小7检测demo
 */
class X7DetectionDemo
{

    /**
     * @var Client
     */
    protected $client;

    protected $requestUrl = "http://api.x7sy.com/x7Detection/gateway";

    protected $testRequestUrl = "http://api.x7sy.com/x7DetectionHelper/gateway";

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function sendMessageDetectRequest()
    {
        try {
            $messageDetectRequest = MessageDetectRequest::make(new ArrayParamHandler([
                "guid" => 123,
                "detectionMessage" => "小傻瓜"
            ]));

            $verifiedResponse = $this->client->request($messageDetectRequest, $this->testRequestUrl);

            $response = (new MessageDetectResponse)->validate(new ArrayParamHandler($verifiedResponse->bizResp));

            //校验请求响应状态
            if ($response->respCode != ResponseCode::SUCCESS) {
                //do something
            }

            //检查结果
            if ($response->detectResult->level == Level::PASS) {
                // do something...
            }

            //违规分类
            if (in_array($response->detectResult->labelCode, [LabelCode::PORN, LabelCode::POLITICAL])) {
                // do something
            }

            var_dump($response);

        } catch (Exception $e) {
            //异常处理
            if ($e instanceof ServerResponseException) {
                echo $e->getContext();
            }
            // echo $e->getMessage();
        }
    }


    public function sendMessageDetectReportRequest()
    {
        try {
            $messageDetectReportRequest = MessageDetectReportRequest::make(new ArrayParamHandler([
                "detectionLogId" => 4728,
                "operateType" => OperateType::BLOCK
            ]));

            $verifiedResponse = $this->client->request($messageDetectReportRequest, $this->testRequestUrl);

            $response = (new MessageDetectReportResponse)->validate(new ArrayParamHandler($verifiedResponse->bizResp));

            //校验请求响应状态
            if ($response->respCode != ResponseCode::SUCCESS) {
                //do something
            }

            var_dump($response);
        } catch (Exception $e) {
            //异常处理
            if ($e instanceof ServerResponseException) {
                echo $e->getContext();
            }
            // echo $e->getMessage();
        }
    }

}