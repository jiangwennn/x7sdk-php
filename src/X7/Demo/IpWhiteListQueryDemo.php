<?php

namespace X7\Demo;

use Exception;
use X7\Client;
use X7\Constant\ResponseCode;
use X7\Exception\ServerResponseException;
use X7\Handler\ArrayParamHandler;
use X7\Module\Common\Constant\IpWhiteListQueryType;
use X7\Module\Common\Request\IpWhiteListQueryRequest;
use X7\Module\Common\Response\IpWhiteListQueryResponse;

class IpWhiteListQueryDemo
{

    /**
     * @var Client
     */
    protected $client;

    protected $requestUrl = "https://api.x7sy.com/vendorApi/gateway";

    protected $testRequestUrl = "https://api.x7sy.com/vendorApi/sample";


    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function sendIpWhiteListQueryRequest()
    {
        try {
            $ipWhiteListQueryRequest = IpWhiteListQueryRequest::make(new ArrayParamHandler([
                "ipType" => IpWhiteListQueryType::CLIENT
            ]));

            $verifiedResponse = $this->client->request($ipWhiteListQueryRequest, $this->testRequestUrl);

            $response = (new IpWhiteListQueryResponse)->validate(new ArrayParamHandler($verifiedResponse->bizResp));

            //校验请求响应状态
            if ($response->respCode != ResponseCode::SUCCESS) {
                //do something
            }

            $response->ipList;

            var_dump($response);
        } catch (Exception $e) {
            //异常处理
            if ($e instanceof ServerResponseException) {
                echo $e->getContext();
            }
            echo $e->getMessage();
        }
    }

}