<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Logger\Laravel\Listeners;

use Jiannei\Logger\Laravel\Events\RequestHandledEvent;
use Jiannei\Logger\Laravel\Repositories\Enums\LogEnum;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestHandledListener
{
    public function handle(RequestHandledEvent $event)
    {
        $request = $event->request;
        $response = $event->response;

        $start = $request->server('REQUEST_TIME_FLOAT');
        $end = microtime(true);
        $context = [
            'request' => $request->all(),
            'response' => $response instanceof SymfonyResponse ? json_decode($response->getContent(), true) : (string) $response,
            'start' => $start,
            'end' => $end,
            'duration' => formatDuration($end - $start),
        ];

        logger_async(LogEnum::REQUEST, $context);
    }
}
