<?php



namespace Jiannei\Logger\Laravel\Http\Middleware;

use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;
use Jiannei\Logger\Laravel\Events\RequestHandledEvent;
use Closure;
use Illuminate\Http\Request;

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
