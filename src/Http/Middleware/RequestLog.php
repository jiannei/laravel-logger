<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Logger\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;
use Jiannei\Logger\Laravel\Events\RequestHandledEvent;

class RequestLog
{
    public function handle(Request $request, Closure $next)
    {
        event(new RequestArrivedEvent($request));

        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        event(new RequestHandledEvent($request, $response));
    }
}
