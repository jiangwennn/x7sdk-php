<?php

require_once './vendor/autoload.php';

use X7\Client;
use X7\Constant\GameType;
use X7\Constant\OsType;
use X7\Demo\RoleQueryDemo;
use X7\Demo\X7DetectionDemo;
use X7\Demo\X7mallDemo;


$appkey = "";
$gameRsaPrivateKey = "";
$x7PublicKey = "";
$gameType = GameType::CLIENT;
// $osType = OsType::ANDROID;
$osType = "";


$client = new Client($appkey, $gameRsaPrivateKey, $x7PublicKey, $gameType, $osType);

// 小7商城
$demo = new X7mallDemo($client);
// $demo->incomingRequest();
$demo->sendMallEntryRequest();


// 小7检测
// $demo = new X7DetectionDemo($client);
// $demo->sendMessageDetectReportRequest();


// 角色查询V2
// $demo = new RoleQueryDemo($client);
// $demo->incomingRequest();