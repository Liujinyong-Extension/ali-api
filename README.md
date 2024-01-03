<h1 align="center"> ali-api </h1>

<p align="center"> 阿里云整合API.</p>


## Installing

```shell
$ composer require liujinyong/ali-api 
```


## 整合阿里云API

### 1号码认证服务-[短信认证功能](https://help.aliyun.com/zh/pnvs/product-overview/message-authentication?spm=a2c4g.11186623.0.0.6ca14130yQthsP)
```php
    //发送短信
    $client = new \Liujinyong\AliApi\core\number_authentication\Message("","","","");
    $res = $client->send("");
    $res->toMap()
    //验证短信
    $client = new \Liujinyong\AliApi\core\number_authentication\Message("","","","");
    $res = $client->check("");
    $res->toMap()
```

### 2.号码认证服务-[号码认证功能](https://help.aliyun.com/zh/pnvs/product-overview/number-authentication?spm=a2c4g.11186623.0.0.4a4f474c3u5Ytu)
```php

    //获取手机号
    $client = new \Liujinyong\AliApi\core\one_time_login\Login("","");
    $res = $client->getPhone("");
    
    //校验手机号
    $client = new \Liujinyong\AliApi\core\one_time_login\Login("","");
    $res = $client->checkPhone("","");
```


### 3.融合认证服务-[融合认证功能](https://help.aliyun.com/zh/pnvs/product-overview/integration-authentication-function?spm=a2c4g.11186623.0.0.72d548feiubANN)
```php

    //获取token
    $client = new \Liujinyong\AliApi\core\rong_authentication\Rong("","");
    $res = $client->gettoken("Android",1000,'',"","");

    
    //获取手机号
    $client = new \Liujinyong\AliApi\core\rong_authentication\Rong("","");
    $res = $client->getPhone("");
```


## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/liujinyong/ali-api/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/liujinyong/ali-api/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT