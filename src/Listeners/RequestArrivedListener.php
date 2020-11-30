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

use Illuminate\Support\Str;
use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;

class RequestArrivedListener
{
    public function handle(RequestArrivedEvent $event)
    {
        $uniqueId = $event->request->headers->get('X-Unique-Id') ?: Str::uuid()->toString();

        $event->request->server->set('UNIQUE_ID', $uniqueId);
    }
}
