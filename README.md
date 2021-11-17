# 小7手游接入PHP SDK



#### 引入方式

##### 	使用composer引入

​	`composer require x7sy/x7`



##### 直接下载release

​	[Releases · jiangwennn/x7sdk-php (github.com)](https://github.com/jiangwennn/x7sdk-php/releases)



#### 使用示例

​	具体逻辑参见`src/X7/Demo`目录下的示例demo类

```php
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
```

