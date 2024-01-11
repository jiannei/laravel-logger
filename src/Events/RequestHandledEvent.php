<?php

/*
 * This file is part of the Jiannei/laravel-logger.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\Logger\Laravel\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class RequestHandledEvent extends Event
{
    public $request;
    public $response;

    public function __construct(Request $request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
