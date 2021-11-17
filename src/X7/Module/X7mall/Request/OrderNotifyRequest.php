<?php

namespace X7\Module\X7mall\Request;

use X7\Exception\ParameterException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\X7mall\Constant\ApiMethod;
use X7\Request\RequestInterface;
use X7\Utils\Traits\ToArray;

/**
 * 订单消费成功通知
 */
class OrderNotifyRequest implements RequestInterface
{

    use ToArray;

    /**
     * 订单号，game_orderid
     * 
     * @var string
     */
    public $orderId;

    /**
     * 订单id goid
     * 
     * @var int
     */
    public $x7Goid;

    /**
     * 小号id
     * 
     * @var int
     */
    public $guid;

    /**
     * 角色id
     * 
     * @var string
     */
    public $roleId;

    /**
     * 角色名称
     * 
     * @var string
     */
    public $roleName;

    /**
     * 区服id
     * 
     * @var string
     */
    public $serverId;

    /**
     * 区服名称
     * 
     * @var string
     */
    public $serverName;

    /**
     * 活动名称
     * 
     * @var string
     */
    public $activityName;

    /**
     * 订单商品信息
     * 
     * @var string
     */
    public $subject;

    /**
     * 价格
     * 
     * @var string
     */
    public $price;

    /**
     * 使用的代金券面值
     * 
     * @var string
     */
    public $couponValue;

    /**
     * 实际支付金额
     * 
     * @var string
     */
    public $payPrice;

    /**
     * 订单创建时间
     * 
     * @var string
     */
    public $createTime;

    /**
     * 订单成功支付时间
     * 
     * @var string
     */
    public $successTime;


    public function getApiMethod()
    {
        return ApiMethod::ORDER_NOTIFY;
    }


    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     */
    public static function make(ParamHandlerInterface $paramHandler)
    {
        $request = new self;
        $needFields = array_keys($request->toArray());
        $diffArr = array_reduce($needFields, function($result, $key) use ($paramHandler, $request){
            $paramFilter = !in_array($key, ["price", "couponValue", "payPrice"]) ? 
            function($item) {
                return (string) $item;
            } : function($item){
                return number_format($item, 2);
            };
            $request->{$key} = $paramHandler->getInputValue($key, $paramFilter);
            //serverName允许为空
            if (empty($request->{$key}) && $key != "serverName") {
                $result[] = $key;
            }
            return $result;
        }, []);
        
        if (!empty($diffArr)) {
            throw new ParameterException("订单成功通告必要参数缺失：". implode(",", $diffArr));
        }

        return $request;
    }
}