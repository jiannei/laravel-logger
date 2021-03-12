<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Logger\Laravel;

use Carbon\Carbon;
use MongoDB\Client;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;

class MongoLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     *
     * @return Logger
     */
    public function __invoke(array $config)
    {
        $authorization = ($config['username'] && $config['password']) ? "{$config['username']}:{$config['password']}@" : '';
        $uri = "mongodb://{$authorization}{$config['host']}:{$config['port']}";

        $collection = null;
        switch ($config['separate']) {
            case 'daily':
                $collection = Carbon::now()->format('Ymd').'_log';
                break;
            case 'monthly':
                $collection = Carbon::now()->format('Ym').'_log';
                break;
            case 'yearly':
                $collection = Carbon::now()->format('Y').'_log';
                break;
            default:
                $collection = 'logs';
        }

        $handler = new MongoDBHandler(new Client($uri), $config['database'], $collection);
        $handler->setLevel($config['level']);

        $logger = new Logger($config['channel']);
        $logger->pushHandler($handler);

        return $logger;
    }
}
