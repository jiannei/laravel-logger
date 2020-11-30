<?php



namespace Jiannei\Logger\Laravel\Listeners;

use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;
use Illuminate\Support\Str;

class RequestArrivedListener
{
    public function handle(RequestArrivedEvent $event)
    {
        $uniqueId = $event->request->headers->get('X-Unique-Id') ?: Str::uuid()->toString();

        $event->request->server->set('UNIQUE_ID', $uniqueId);
    }
}
