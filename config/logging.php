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

            'host' => env('LOG_MONGODB_HOST', config('database.connections.mongodb.host')),
            'port' => env('LOG_MONGODB_PORT', config('database.connections.mongodb.port')),
            'username' => env('LOG_MONGODB_USERNAME', config('database.connections.mongodb.username')),
            'password' => env('LOG_MONGODB_PASSWORD', config('database.connections.mongodb.password')),
            'database' => env('LOG_MONGODB_DATABASE', config('database.connections.mongodb.database')),
        ],
    ],

    'enum' => \Jiannei\Enum\Laravel\Repositories\Enums\LogEnum::class,

    'query' => [
        'enabled' => env('LOG_QUERY', false),

        // Only record queries that are slower than the following time
        // Unit: milliseconds
        'slower_than' => 0,
    ],

    'request' => [
        'enabled' => env('LOG_REQUEST', false),
    ],
];
