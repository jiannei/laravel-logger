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

use Illuminate\Support\Facades\Config;
use Jiannei\Logger\Laravel\Events\RequestHandledEvent;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestHandledListener
{
    public function handle(RequestHandledEvent $event)
    {
        $start = $event->request->server('REQUEST_TIME_FLOAT');
        $end = microtime(true);

        $request = $event->request->all();
        if ($files = $event->request->allFiles()) {
            foreach ($files as $key => $uploadedFile) {
                $request[$key] = [
                    'originalName' => $uploadedFile->getClientOriginalName(),
                    'mimeType' => $uploadedFile->getClientMimeType(),
                ];
            }
        }

        $context = [
            'request' => $request,
            'response' => $event->response instanceof SymfonyResponse ? json_decode($event->response->getContent(), true) : (string) $event->response,
            'start' => $start,
            'end' => $end,
            'duration' => formatDuration($end - $start),
        ];

        /**
         * @var \Jiannei\Enum\Laravel\Repositories\Enums\LogEnum $logEnumClass
         */
        $message = 'system:request';
        if (class_exists($logEnumClass = Config::get('logging.enum'))) {
            $message = $logEnumClass::getDescription($logEnumClass::SYSTEM_REQUEST);
        }

        logger_async($message, $context);
    }
}
