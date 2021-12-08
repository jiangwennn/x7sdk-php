<?php

namespace X7\Module\Common\Response;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\Common\Constant\ApiMethod;
use X7\Response\BizRespValidatorInterface;
use X7\Response\CommonResponse;

class IpWhiteListQueryResponse extends CommonResponse implements BizRespValidatorInterface
{
    protected $apiMethod = ApiMethod::IP_WHITELIST_QUERY;

    /**
     * ip白名单
     *
     * @var array
     */
    public $ipList = [];


    public function appendIpList($ip)
    {
        if (ip2long($ip) === false) {
            throw new RuntimeException("ip：{$ip}不合法");
        }
        $this->ipList[] = $ip;
        return $this;
    }

    public function setIpList($ipList)
    {
        if (!is_array($ipList)) {
            throw new RuntimeException("ipList参数有误");
        }
        foreach ($ipList as $ip) {
            if (!is_string($ip)) {
                throw new RuntimeException("ip参数有误");
            }
            $this->appendIpList($ip);
        }
        return $this;
    }


    /**
     * 校验
     *
     * @param ParamHandlerInterface $bizResp
     * @return self
     * @throws RuntimeException
     */
    public function validateBizResp(ParamHandlerInterface $bizResp)
    {
        return $this->setIpList($bizResp->getInputValue("ipList"));
    }

}