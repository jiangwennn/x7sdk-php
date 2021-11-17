<?php

namespace X7\Module\X7mall\Response;

use X7\Module\X7mall\Constant\ApiMethod;
use X7\Response\CommonResponse;

/**
 * 订单消费成功通知
 */
class OrderNotifyResponse extends CommonResponse
{

    protected $apiMethod = ApiMethod::ORDER_NOTIFY;

}