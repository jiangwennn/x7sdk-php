<?php

namespace X7\Module\Basic\Request;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\Basic\Constant\RequestMethod;
use X7\Module\Basic\Model\BasicRequest;
use X7\Request\BasicRequestInterface;
use X7\Utils\ParamTool;

class CheckGameOrderRequest implements BasicRequestInterface
{
    protected $x7PublicKey;

    protected $sign;

    protected $requestUrl = "https://api.x7sy.com/game/chk_game_order";

    public $appkey;

    public $game_orderid;



    public static function make(ParamHandlerInterface $paramHandler)
    {
        $appkey = $paramHandler->getInputValue("appkey");
        $gameOrderId = $paramHandler->getInputValue("gameOrderId");
        $x7PublicKey = $paramHandler->getInputValue("x7PublicKey");
        $requestUrl = $paramHandler->getInputValue("requestUrl");

        if (empty($x7PublicKey) || !ParamTool::isValidPublicKey($x7PublicKey)) {
            throw new RuntimeException("RSA公钥有误");
        }
        if (empty($gameOrderId) || !is_string($gameOrderId)) {
            throw new RuntimeException("游戏订单号参数有误");
        }
        if (empty($appkey) || !is_string($appkey) || !ParamTool::isValidAppkey($appkey)) {
            throw new RuntimeException("appkey参数有误");
        }

        $self = new self;
        if (!empty($requestUrl)) {
            $self->requestUrl = $requestUrl;
        }
        $self->appkey = $appkey;
        $self->game_orderid = $gameOrderId;
        $self->x7PublicKey = $x7PublicKey;
        $self->sign = $self->getSign();
        return $self;
    }

    private function getSign()
    {
        $signArr = [
            "appkey" => $this->appkey,
            "game_orderid" => $this->game_orderid,
        ];
        ksort($signArr);
        $queryString = ParamTool::buildQueryString($signArr);
        return md5($queryString . $this->x7PublicKey);
    }


    public function getRequest()
    {
        return (new BasicRequest)
            ->setUrl($this->requestUrl)
            ->setMethod(RequestMethod::GET)
            ->setBody([
                "appkey" => $this->appkey,
                "game_orderid" => $this->game_orderid,
                "sign" => $this->sign,
            ]);
    }


}