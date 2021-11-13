<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Logger\Laravel\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;
use Jiannei\Logger\Laravel\Events\RequestHandledEvent;
use Jiannei\Logger\Laravel\Listeners\RequestArrivedListener;
use Jiannei\Logger\Laravel\Listeners\RequestHandledListener;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $this->logQuery();
        $this->logRequest();
    }

    protected function logQuery()
    {
        if (! $this->app['config']->get('logging.query.enabled', false)) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            if ($query->time < $this->app['config']->get('logging.query.slower_than', 0)) {
                return;
            }

            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = format_duration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }

            $context = [
                'database' => $query->connection->getDatabaseName(),
                'duration' => $duration,
                'sql' => $realSql,
            ];

            logger_async(\config('logging.query.message'), $context)
                ->onConnection(\config('logging.query.connection'))
                ->onQueue(\config('logging.query.queue'));
        });
    }

    protected function logRequest()
    {
        if (! $this->app['config']->get('logging.request.enabled', false)) {
            return;
        }

        $this->app['events']->listen(RequestArrivedEvent::class, RequestArrivedListener::class);
        $this->app['events']->listen(RequestHandledEvent::class, RequestHandledListener::class);
    }
}
