<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (!function_exists('logger_async')) {
    /**
     * Log a debug message to the logs.
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch|mixed
     */
    function logger_async(string $message, array $context = [])
    {
        return dispatch(new \Jiannei\Logger\Laravel\Jobs\LogJob($message, $context, request()->server()));
    }
}
