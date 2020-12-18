<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (! function_exists('logger_async')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return \Illuminate\Foundation\Bus\PendingDispatch|mixed
     */
    function logger_async(string $message, array $context = [])
    {
        return dispatch(new \Jiannei\Logger\Laravel\Jobs\LogJob($message, $context, request()->server()));
    }
}

if (! function_exists('formatDuration')) {
    /**
     * Format duration.
     *
     * @param  float  $seconds
     *
     * @return string
     */
    function formatDuration(float $seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000).'μs';
        }

        if ($seconds < 1) {
            return round($seconds * 1000, 2).'ms';
        }

        return round($seconds, 2).'s';
    }
}
