<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'channels' => [
        'mongo' => [
            'driver' => 'custom', // 此处必须为 custom
            'via' => \Jiannei\Logger\Laravel\MongoLogger::class, // 当 driver 设置为 custom 时，使用 via 配置项所指向的工厂类创建 logger

            'channel' => env('LOG_MONGODB_CHANNEL', 'mongo'),
            'level' => env('LOG_MONGODB_LEVEL', 'debug'), // 日志级别
            'separate' => env('LOG_MONGODB_SEPARATE', false), // false,daily,monthly,yearly

            'host' => env('LOG_MONGODB_HOST', '127.0.0.1'),
            'port' => env('LOG_MONGODB_PORT', 27017),
            'username' => env('LOG_MONGODB_USERNAME', ''),
            'password' => env('LOG_MONGODB_PASSWORD', ''),
            'database' => env('LOG_MONGODB_DATABASE', ''),
        ],
    ],

    'query' => [
        'enabled' => env('LOG_QUERY', false),
        'message' => 'query',
        'connection' => env('LOG_QUERY_CONNECTION', 'default'), // queue connection
        'queue' => env('LOG_QUERY_QUEUE', 'default'), // queue name

        // Only record queries that are slower than the following time
        // Unit: milliseconds
        'slower_than' => 0,
    ],

    'request' => [
        'enabled' => env('LOG_REQUEST', false),
        'message' => 'request',
        'connection' => env('LOG_REQUEST_CONNECTION', 'default'), // queue connection
        'queue' => env('LOG_REQUEST_QUEUE', 'default'), // queue name
    ],
];
