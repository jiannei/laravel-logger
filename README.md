<h1 align="center"> laravel-logger </h1>

![Test](https://github.com/Jiannei/laravel-logger/workflows/Test/badge.svg)
[![StyleCI](https://github.styleci.io/repos/317144680/shield?branch=main)](https://github.styleci.io/repos/317144680?branch=main)

## 介绍

`laravel-logger` 主要用来扩展项目中的日志记录，使调试更加方便。

## 概览

- 提供 `logger_async` 辅助函数，通过异步 Job 方式来记录日志；
- 增加 RequestLog 中间件来记录 api 的请求和响应；对于单个请求关联 `UNIQUE_ID`，根据`UNIQUE_ID`可以跟踪请求执行过程
- 适配 MongoDB 驱动，支持记录日志到 MongoDB；collection 支持按天、按月和按年拆分；
- 日志的 message 适配 [laravel-enum](https://github.com/Jiannei/laravel-enum)，来让所有的日志来源可以追溯
- 提供 `LOG_QUERY`、`LOG_REQUEST` 配置参数来开启关闭 sql 日志和 request 日志

## 安装

支持 Laravel 8/Lumen 8 以上版本，自定义日志描述分依赖于  [jiannei/laravel-enum](https://github.com/Jiannei/laravel-enum)，需要先进行安装。


```shell
$ composer require jiannei/laravel-enum -vvv
$ composer require jiannei/laravel-logger -vvv
```


## 配置

复制配置项到 `config/logging.php`中，参考：https://github.com/Jiannei/lumen-api-starter/blob/master/config/logging.php

### Laravel

- 添加中间件 RequestLog 来记录 API 请求日志

在 `app/Http/Kernel.php` 的 $middlewareGroups 中添加

```php
protected $middlewareGroups = [
    'api' => [
        \Jiannei\Logger\Laravel\Http\Middleware\RequestLog::class,// 加在这个地方
    ],
];
```

-

### Lumen

- 加载配置

```php
// bootstrap/app.php
$app->configure('logging');
```

- 添加中间件

```php
$app->middleware([
    \Jiannei\Logger\Laravel\Http\Middleware\RequestLog::class,
]);

```

- 注册服务容器

```php
$app->register(\Jiannei\Logger\Laravel\Providers\ServiceProvider::class);
```

### .env 中配置启用

```php
LOG_CHANNEL=mongo
LOG_SLACK_WEBHOOK_URL=
LOG_QUERY=true
LOG_REQUEST=true
LOG_MONGODB_SEPARATE=daily
LOG_MONGODB_LEVEL=debug

# 如果使用的是 mongo channel 需要配置
MONGODB_HOST=mongo
MONGODB_PORT=27017
MONGODB_DATABASE=lumen-api
MONGODB_USERNAME=
MONGODB_PASSWORD=
MONGODB_AUTHENTICATION_DATABASE=admin
```

### 其他

如果需要记录日志到 MongoDB，需要先安装并配置[laravel-mongodb](https://github.com/jenssegers/laravel-mongodb)

## 如何使用

可以参考 [lumen-api-starter](https://github.com/Jiannei/lumen-api-starter) 中的实际使用示例。

### 使用

- `app/Repositories/Enums/LogEnum.php` 中定义记录日志时的 message
- 通过 logger_async 方法记录日志

```php
logger_async(LogEnum::SYSTEM_SQL, $arrayData);
```

- 如果队列任务异步执行，则需要开启队列消费 `php artisan queue:work`

- 记录到文件中的日志内容

```
[2021-01-18 12:03:36] local.DEBUG: System sql {"database":"lumen-api","duration":"11.08ms","sql":"select `roles`.*, `model_has_roles`.`model_id` as `pivot_model_id`, `model_has_roles`.`role_id` as `pivot_role_id`, `model_has_roles`.`model_type` as `pivot_model_type` from `roles` inner join `model_has_roles` on `roles`.`id` = `model_has_roles`.`role_id` where `model_has_roles`.`model_id` = '11' and `model_has_roles`.`model_type` = 'App\\\\Repositories\\\\Models\\\\User'"} {"url":"/users","ip":"172.22.0.1","http_method":"get","server":"lumen-api.test","referrer":null,"unique_id":"43f54ea9-4ad4-47cf-b9da-1d3aa150ff61"}
[2021-01-18 12:03:36] local.DEBUG: System request {"request":[],"response":{"status":"success","code":200,"message":"操作成功","data":{"data":[{"id":1,"nickname":"Evert Stracke DVM","email":"aufderhar.kaden@example.net"},{"id":2,"nickname":"Milton Toy","email":"keagan.eichmann@example.org"},{"id":3,"nickname":"Mrs. Alyce O'Hara","email":"cartwright.sidney@example.org"},{"id":4,"nickname":"Prof. Evalyn Windler I","email":"bertram.bartoletti@example.org"},{"id":5,"nickname":"Brant Skiles","email":"jane16@example.net"},{"id":6,"nickname":"Sage Rodriguez I","email":"ryder50@example.org"},{"id":7,"nickname":"Ms. Angelica Wiegand DVM","email":"kaelyn.mueller@example.net"},{"id":8,"nickname":"Newton Zieme","email":"sipes.kip@example.com"},{"id":9,"nickname":"Natalia Ruecker","email":"stroman.kiley@example.com"},{"id":10,"nickname":"Hallie Parisian","email":"rosina74@example.net"},{"id":11,"nickname":"Jiannei","email":"longjian.huang@foxmail.com"}],"meta":{"pagination":{"total":11,"count":11,"per_page":15,"current_page":1,"total_pages":1,"links":[]}}},"error":[]},"start":1610942614.450748,"end":1610942615.785696,"duration":"1.33s"} {"url":"/users","ip":"172.22.0.1","http_method":"GET","server":"lumen-api.test","referrer":null,"unique_id":"43f54ea9-4ad4-47cf-b9da-1d3aa150ff61"}
```

- 记录日志到 Mongodb 的日志内容

```
/* 1 */
{
    "_id" : ObjectId("60050999ee7d025d4c62c8c2"),
    "message" : "System sql",
    "context" : {
        "database" : "lumen-api",
        "duration" : "54.19ms",
        "sql" : "select count(*) as aggregate from `users`"
    },
    "level" : 100,
    "level_name" : "DEBUG",
    "channel" : "mongo",
    "datetime" : ISODate("2021-01-18T12:07:53.410+08:00"),
    "extra" : {
        "url" : "/users",
        "ip" : "172.22.0.1",
        "http_method" : "get",
        "server" : "lumen-api.test",
        "referrer" : null,
        "unique_id" : "0cda1927-bf14-4acf-88e8-1d9ed67170b5"
    }
}

/* 2 */
{
    "_id" : ObjectId("60050999ee7d025d4c62c8c3"),
    "message" : "System sql",
    "context" : {
        "database" : "lumen-api",
        "duration" : "2.42ms",
        "sql" : "select * from `users` limit 15 offset 0"
    },
    "level" : 100,
    "level_name" : "DEBUG",
    "channel" : "mongo",
    "datetime" : ISODate("2021-01-18T12:07:53.500+08:00"),
    "extra" : {
        "url" : "/users",
        "ip" : "172.22.0.1",
        "http_method" : "get",
        "server" : "lumen-api.test",
        "referrer" : null,
        "unique_id" : "0cda1927-bf14-4acf-88e8-1d9ed67170b5"
    }
}
```

## 特别说明

- SQL 日志记录参考：l[aravel-query-logger](https://github.com/overtrue/laravel-query-logger)

## License

MIT