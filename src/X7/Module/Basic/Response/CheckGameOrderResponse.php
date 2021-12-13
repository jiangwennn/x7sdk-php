<?php

namespace X7\Module\Basic\Response;

use X7\Handler\ParamHandlerInterface;
use X7\Response\BasicResponse;
use X7\Module\Basic\Model\GameOrder;
use X7\Response\BizRespValidatorInterface;

class CheckGameOrderResponse extends BasicResponse implements BizRespValidatorInterface
{
    /**
     * @var GameOrder
     */
    public $game_order_data = [];


    public function validateBizResp(ParamHandlerInterface $bizResp)
    {
        $gameOrderData = $bizResp->getInputValue("game_order_data");
        if (!empty($gameOrderData)) {
            $this->game_order_data = GameOrder::make($gameOrderData);
        }
        return $this;
    }
}