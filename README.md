<h1 align="center"> ali-api </h1>

<p align="center"> 阿里云api.</p>


## Installing

```shell
$ composer require liujinyong/ali-api -vvv
```

## Usage

### 1.短信认证使用
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

### 2.一键登录
```php

    //获取手机号
    $client = new \Liujinyong\AliApi\core\one_time_login\Login("","");
    $res = $client->getPhone("");
    
    //校验手机号
    $client = new \Liujinyong\AliApi\core\one_time_login\Login("","");
    $res = $client->checkPhone("","");
```


## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/liujinyong/ali-api/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/liujinyong/ali-api/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT