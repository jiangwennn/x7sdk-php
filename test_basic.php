<?php

require_once './vendor/autoload.php';

use X7\Demo\BasicClientDemo;



$basicDemo = new BasicClientDemo;

$appkey = "";
$x7PublicKey = "";

//登录获取用户信息
// $tokenkey = "";
// $basicDemo->sendCheckLoginRequest($appkey, $tokenkey);

//游戏消费参数签名
// $result = $basicDemo->gamePayRequest($x7PublicKey);
// var_dump($result);

//查询订单信息
// $gameOrderId = "";
// $basicDemo->sendCheckGameOrderRequest($appkey, $gameOrderId, $x7PublicKey);


//支付回调
$basicDemo->handleGamePayCallback($x7PublicKey);


